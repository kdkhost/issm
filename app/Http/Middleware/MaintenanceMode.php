<?php

namespace App\Http\Middleware;

use App\Models\MaintenanceIp;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for admin routes
        if ($request->is('admin*') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        // Check if maintenance mode is enabled
        $maintenanceMode = Setting::get('maintenance_mode', '0');

        if ($maintenanceMode == '1' || $maintenanceMode === true) {
            // Allow if user is logged in as admin
            if (Auth::check() && Auth::user()->is_admin) {
                return $next($request);
            }

            // Allow if IP is whitelisted
            $clientIp = $request->ip();
            if (MaintenanceIp::isAllowed($clientIp)) {
                return $next($request);
            }

            // Show maintenance page
            $title         = Setting::get('maintenance_title',   'Site em Manutenção');
            $message       = Setting::get('maintenance_message', 'Estamos realizando melhorias. Em breve estaremos de volta!');
            $email         = Setting::get('maintenance_email',   '');
            $logo          = Setting::get('maintenance_logo',    '');
            $bgImage       = Setting::get('maintenance_bg_image','');
            $bgColor       = Setting::get('maintenance_bg_color','#14532d');
            $animation     = Setting::get('maintenance_animation','gear');
            $showCountdown = Setting::get('maintenance_show_countdown','0') == '1';
            $returnDate    = Setting::get('maintenance_return_date','');
            $showSocial    = Setting::get('maintenance_show_social','1') == '1';
            $progress      = max(0, min(100, (int) Setting::get('maintenance_progress','65')));
            // redes sociais para exibir na manutenção
            $facebook      = Setting::get('social_facebook','');
            $instagram     = Setting::get('social_instagram','');
            $youtube       = Setting::get('social_youtube','');
            $whatsapp      = Setting::get('social_whatsapp','');

            return response()->view('maintenance', compact(
                'title','message','email',
                'logo','bgImage','bgColor','animation',
                'showCountdown','returnDate',
                'showSocial','progress',
                'facebook','instagram','youtube','whatsapp'
            ), 503);
        }

        return $next($request);
    }
}
