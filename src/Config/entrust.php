<?php

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Entrust Role Model
    |--------------------------------------------------------------------------
    |
    | This is the Role model used by Entrust to create correct relations.  Update
    | the role if it is in a different namespace.
    |
    */
    'role' => 'CoreCMF\Core\Models\Role',

    /*
    |--------------------------------------------------------------------------
    | Entrust Roles Table
    |--------------------------------------------------------------------------
    |
    | This is the roles table used by Entrust to save roles to the database.
    |
    */
    'roles_table' => 'entrust_roles',

    /*
    |--------------------------------------------------------------------------
    | Application User Model
    |--------------------------------------------------------------------------
    |
    | This is the User model used by Entrust to create correct relations.
    | Update the User if it is in a different namespace.
    |
    */
    'user' => 'CoreCMF\Core\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Application Users Table
    |--------------------------------------------------------------------------
    |
    | This is the users table used by the application to save users to the
    | database.
    |
    */
    'users_table' => 'core_users',

    /*
    |--------------------------------------------------------------------------
    | Entrust Permission Model
    |--------------------------------------------------------------------------
    |
    | This is the Permission model used by Entrust to create correct relations.
    | Update the permission if it is in a different namespace.
    |
    */
    'permission' => 'CoreCMF\Core\Models\Permission',

    /*
    |--------------------------------------------------------------------------
    | Entrust Permissions Table
    |--------------------------------------------------------------------------
    |
    | This is the permissions table used by Entrust to save permissions to the
    | database.
    |
    */
    'permissions_table' => 'entrust_permissions',

    /*
    |--------------------------------------------------------------------------
    | Entrust permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by Entrust to save relationship
    | between permissions and roles to the database.
    |
    */
    'permission_role_table' => 'entrust_permission_role',

    /*
    |--------------------------------------------------------------------------
    | Entrust role_user Table
    |--------------------------------------------------------------------------
    |
    | This is the role_user table used by Entrust to save assigned roles to the
    | database.
    |
    */
    'role_user_table' => 'entrust_role_user',


];
