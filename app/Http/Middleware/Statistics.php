<?php

namespace App\Http\Middleware;

use App\Models\Shared\Statistic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

class Statistics
{
    protected $domain = 'http://www.geoplugin.net/php.gp?ip=';

    protected function getAgentsProperties($request): array
    {
        $geo = unserialize(file_get_contents($this->domain . $request->ip()));
        $agent = new Agent();
        return [
            'ip' => $geo['geoplugin_request'] ?? $request->ip(),
            'geo' => [
                'latitude' => $geo['geoplugin_latitude'],
                'longitude' => $geo['geoplugin_longitude'],
                'timezone' => $geo['geoplugin_timezone'],
                'continent_code' => $geo['geoplugin_continentCode'],
                'continent_name' => $geo['geoplugin_continentName'],
                'country_code' => $geo['geoplugin_countryCode'],
                'country_name' => $geo['geoplugin_countryName']
            ],
            'referer_page' => request()->headers->get('referer'),
            'page' => $request->url(),
            'device' => ucfirst($agent->device()),
            'device_type' => ucfirst($agent->deviceType()),
            'platform' => ucfirst($agent->platform()),
            'platform_version' => ucfirst($agent->version($agent->platform())),
            'browser' => ucfirst($agent->browser()),
            'browser_version' => ucfirst($agent->version($agent->browser()))
        ];
    }

    protected function registerStatistic($agents)
    {
        $statistic = Statistic::whereDate('created_at', Carbon::today())->where([
            'ip' => $agents['ip'],
            'page' => $agents['page'],
            'device' => $agents['device'],
            'device_type' => $agents['device_type'],
            'platform' => $agents['platform'],
            'platform_version' => $agents['platform_version'],
            'browser' => $agents['browser'],
            'browser_version' => $agents['browser_version']
        ])->first();
        if ($statistic) {
            $statistic->touch();
        } else {
            Statistic::create($agents);
        }
    }

    public function handle(Request $request, Closure $next)
    {
        if (isDomainAvailable($this->domain)) {
            $this->registerStatistic($this->getAgentsProperties($request));
        }
        return $next($request);
    }
}
