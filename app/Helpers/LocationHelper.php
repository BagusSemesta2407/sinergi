<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Parse coordinates from string
     */
    public static function parseCoordinates($location)
    {
        if (!$location) return null;
        
        $parts = explode(',', $location);
        if (count($parts) >= 2) {
            $lat = trim($parts[0]);
            $lng = trim($parts[1]);
            
            if (is_numeric($lat) && is_numeric($lng)) {
                return [
                    'latitude' => (float) $lat,
                    'longitude' => (float) $lng,
                    'formatted' => number_format($lat, 6) . ', ' . number_format($lng, 6)
                ];
            }
        }
        
        return [
            'address' => $location,
            'is_address' => true
        ];
    }
    
    /**
     * Get distance between two coordinates in meters
     */
    public static function getDistance($coord1, $coord2)
    {
        if (!$coord1 || !$coord2) return null;
        
        $parsed1 = self::parseCoordinates($coord1);
        $parsed2 = self::parseCoordinates($coord2);
        
        if (!$parsed1 || !$parsed2 || isset($parsed1['is_address']) || isset($parsed2['is_address'])) {
            return null;
        }
        
        $lat1 = $parsed1['latitude'];
        $lon1 = $parsed1['longitude'];
        $lat2 = $parsed2['latitude'];
        $lon2 = $parsed2['longitude'];
        
        $earthRadius = 6371000; // Earth's radius in meters
        
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        
        return $angle * $earthRadius;
    }
    
    /**
     * Format distance for display
     */
    public static function formatDistance($meters)
    {
        if ($meters === null) return 'Tidak tersedia';
        
        if ($meters < 1000) {
            return round($meters) . ' meter';
        } else {
            return round($meters / 1000, 2) . ' km';
        }
    }
    
    /**
     * Check if location is valid coordinates
     */
    public static function isValidCoordinates($location)
    {
        $parsed = self::parseCoordinates($location);
        return $parsed && !isset($parsed['is_address']);
    }
    
    /**
     * Get address from coordinates using Nominatim (reverse geocoding)
     */
    public static function getAddressFromCoordinates($lat, $lng)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AbsensiWFA-App');
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        return $data['display_name'] ?? null;
    }
}