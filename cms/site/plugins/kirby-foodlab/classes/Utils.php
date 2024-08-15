<?php

namespace MediumSans\Menu;

class Utils
{
    public static function checkRoleAccess($route, $path, $method, $pluginPermissionNameForBlueprint): void
    {
        $deniedAccessRedirectionPath = 'foodlab/denied-access';

        //  limit to this plugin
        if($path === null) return;
        if($path == $deniedAccessRedirectionPath) return;
        if( ! str_starts_with($path, 'foodlab') ) return;

        if( !kirby()->user()) return;

        $currentUserRolePermissions = kirby()->user()->role()->permissions()->toArray();
        if( ! array_key_exists($pluginPermissionNameForBlueprint, $currentUserRolePermissions ) ) return;

        $userRoleCanAccessToThisPlugin = kirby()->user()->role()->permissions()->for($pluginPermissionNameForBlueprint, 'access');

        if( ! $userRoleCanAccessToThisPlugin ) go("panel/$deniedAccessRedirectionPath");
    }

}
