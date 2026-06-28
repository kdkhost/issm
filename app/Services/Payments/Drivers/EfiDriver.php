<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EfiDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'efi'; }

    public function supportedMethods(): array { return ['pix']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        [$baseUrl, $clientId, $clientSecret] = $this->credentials();

        // Obter token OAuth2
        $tokenResponse = Http::withBasicAuth($clientId, $clientSecret)
            ->asForm()
            ->post($baseUrl . '/v1/authorize', ['grant_type' => 'client_credentials']);

        if (! $tokenResponse->successful()) {
            throw new RuntimeException('Efi: nao foi possivel obter token de acesso: ' . $tokenResponse->body());
        }

        $accessToken = (string) $tokenResponse->json('access_token');
        $reference   = $this->reference($support);

        // Criar cobrança PIX imediata (cob)
        $txid    = 'ISSM' . preg_replace('/[^a-zA-Z0-9]/', '', $support->id . substr($reference, -8));
        $txid    = substr($txid, 0, 35);

        $response = Http::withToken($accessToken)
            ->put($baseUrl . '/v2/cob/' . $txid, [
                'calendario'    => ['expiracao' => 86400],
                'devedor'       => [
                    'nome'      => $support->name,
                    'cpf'       => $support->document ? preg_replace('/\D/', '', $support->document) : null,
                ],
                'valor'         => ['original' => number_format((float) $support->amount, 2, '.', '')],
                'chave'         => Setting::get('donation_efi_pix_key', ''),
                'solicitacaoPagador' => 'Doacao para ' . optional($support->project)->title,
                'infoAdicionais'=> [
                    ['nome' => 'Referencia', 'valor' => $reference],
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Efi recusou a cobranca PIX: ' . $response->body());
        }

        $payload = $response->json();

        // Gerar QR Code
        $qrResponse = Http::withToken($accessToken)
            ->get($baseUrl . '/v2/cob/' . $txid . '/qrcode');

        $qrData = $qrResponse->successful() ? $qrResponse->json() : [];

        return $this->persist($support, [
            'gateway'     => 'efi',
            'method'      => 'pix',
            'status'      => $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending',
            'external_id' => $txid,
            'reference'   => $reference,
            'payment_url' => data_get($qrData, 'linkVisualizacao'),
            'payload'     => array_merge($payload, ['qr_data' => $qrData]),
            'public'      => [
                'type'           => 'pix',
                'qr_code'        => data_get($qrData, 'qrcode'),
                'qr_code_base64' => data_get($qrData, 'imagemQrcode'),
                'ticket_url'     => data_get($qrData, 'linkVisualizacao'),
                'message'        => 'Escaneie o QR Code PIX para concluir a doacao.',
            ],
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $pixList    = $request->input('pix', []);
        $firstPix   = $pixList[0] ?? [];

        $externalId = data_get($firstPix, 'txid') ?: $request->input('txid');
        $status     = data_get($firstPix, 'status') ?: 'paid'; // webhook só dispara ao pagar

        // Extrair referência dos infoAdicionais
        $reference = null;
        foreach (data_get($firstPix, 'infoAdicionais', []) as $info) {
            if (($info['nome'] ?? '') === 'Referencia') {
                $reference = $info['valor'] ?? null;
                break;
            }
        }

        return [
            'external_id' => $externalId ? (string) $externalId : null,
            'reference'   => $reference,
            'status'      => $this->normalizeStatus((string) $status) ?: 'paid',
            'payload'     => $request->all(),
        ];
    }

    private function credentials(): array
    {
        $mode        = Setting::get('donation_efi_mode', 'production');
        $baseUrl     = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_efi_base_url_sandbox' : 'donation_efi_base_url',
            $mode === 'sandbox' ? 'https://pix-h.api.efipay.com.br' : 'https://pix.api.efipay.com.br'
        ));
        $clientId     = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_efi_client_id_sandbox' : 'donation_efi_client_id', ''
        ));
        $clientSecret = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_efi_api_key_sandbox' : 'donation_efi_api_key', ''
        ));

        if (! $clientId || ! $clientSecret) {
            throw new RuntimeException('Configure Client ID e API Key da Efi no painel administrativo.');
        }

        return [rtrim($baseUrl, '/'), $clientId, $clientSecret];
    }
}
