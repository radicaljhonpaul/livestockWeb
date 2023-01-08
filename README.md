## About Project

A web application for QRT

## Tools

- LARAVEL v8
- VUEJS/INERTIAJS - V3
- JAVASCRIPT
- JQUERY (latest)
- BOOTSTRAP 4.6
- Moment.js
- AdminLTE Bootrap (Admin Dashboard)

## Prerequisite

- PHP 8
- MYSQL (Database) 8
- NPM (NodeJS)
- Composer

## Installation & Running Project

- Start Apache&MySQL or Start XAMPP
- Clone Project
- Import "livestockweb.sql" file inside "SQL_FILES" Folder
- go to your project folder and run CMD
- Run Commands
    - npm install 
    - composer install
    - php artisan config:cache
    - php artisan cache:clear
    - php artisan view:clear
- php artisan serve
- npm run hot

## Scaffold and Pointers
MVVM (Model View – View Model) Structure since we are using Laravel for Backend and VueJS/InertiaJS for Frontend as Framework.   

- routes/api.php (API Routes)
// We will declare all the database queries for the mobile app

- routes/web.php (Web Routes)
// Here you can see all the routes or the path to the Controllers (Backend Side)
// Serves as the connector between PHP Controller and Laravel View

- Http/Controllers/{Folder_Name}/{Controller_Name} (PHP Controllers)
// All the back-end process and Database Insertion and Manipulation happens here.
// All functions for Database CRUD happens here.

- Model/{Model_Name} (Database Schema/Models)
// All database table connection and relationships happens here.

- resources/js/Layouts/{Layouts_Name} (Navigations)
// Set up for different Navigations for diff. users.
    // AdminLayout.vue | for Admin Staff
    // HQLayout.vue | for PCC HQ Staff
    // VetFieldLayout.vue | for Vet or Field Staff
    // ExtWorkersLayout.vue | for Extension Workers Staff

- resources/js/Pages/{Pages_Name} (Pages)
// Different Pages to be rendered in the view or main slot

- Start of Sample Page Structure
<template> - Default (Used for rendering the app.blade.php)
    <admin-layout> (- Declared Vue Component)
        <div class="content-wrapper"> (- Normal HTML Tags)
            <h2 class="h4 font-weight-bold">
                Admin Dashboard
            </h2>
        </div>
    </admin-layout>
</template>

<script> - Scripttag
    import AdminLayout from '@/Layouts/AdminLayout' (- Imported Vue Components)

    export default {
        components: { (- Vue Components)
            AdminLayout,
        },
        data() { (- Variables for Declaration )
            return {
                Variable_a: 1,
                Variable_b: 2,
            }
        },
        methods: { (- Functions for frontend)
            methods_1() {
                // Function...
            },
        }
    }
</script>
- End of Sample Page Structure

- resources/js/app.js
// Declaration of vue components and plugins

- resources/js/bootstrap.js
// Declaration of bootstrap and it's dependencies.
// Hannah we will also uncomment "laravel-echo" (equal to socket.io) lines onece we have set up the realtime fetching og data from the mobile app.

- .env
// Environmental Variables for the project.
// Database Declaration etc.

## InertiaJS
// Inertia allows you to create fully client-side rendered, single-page apps, without much of the complexity that comes with modern SPAs (Single Page Application);

- Sample Form
<form @submit.prevent="submit">
<div class="form-group">
    <jet-label for="name" value="Name" />
    <jet-input id="name" type="text" v-model="form.name" required autofocus autocomplete="name" />
</div>

<div class="form-group">
    <jet-label for="email" value="Email" />
    <jet-input id="email" type="email" v-model="form.email" required />
</div>

<div class="form-group">
    <jet-label for="role" value="Role" />
    <select id="role" type="text" class="form-control" name="role" v-model="form.role" required>
    <option value="admin">Admin</option>
    <option value="pcc_hq">PCC HQ</option>
    <option value="pcc_vet_field">PCC Vet/Field</option>
    <option value="pcc_ext_workers">Extension Workers</option>
    </select>
</div>

<div class="form-group">
    <jet-label for="password" value="Password" />
    <jet-input id="password" type="password" v-model="form.password" required autocomplete="new-password" />
</div>

<div class="form-group">
    <jet-label for="password_confirmation" value="Confirm Password" />
    <jet-input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password" />
</div>

<div class="form-group" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
<div class="custom-control custom-checkbox">
<jet-checkbox name="terms" id="terms" v-model:checked="form.terms" />

<label class="custom-control-label" for="terms">
    I agree to the <a target="_blank" :href="route('terms.show')">Terms of Service</a> and <a target="_blank" :href="route('policy.show')">Privacy Policy</a>
</label>
</div>
</div>

<div class="mb-0">
    <div class="d-flex justify-content-end align-items-baseline">
    <inertia-link :href="route('login')" class="text-muted mr-3 text-decoration-none">
        Already registered?
    </inertia-link>

    <jet-button class="ml-4" :class="{ 'text-white-50': form.processing }" :disabled="form.processing">
        Register
    </jet-button>
    </div>
</div>
</form>


- Sample Inertia Func for Sending Data to PHP Controller

// Declared Data of Vue "Data" Method
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        email: '',
        password: '', 
        role: null,
        password_confirmation: '',
        terms: false,
      })
    }
  },

// Inertia Method (Same as axios or Ajax) - Passing Data to "this.route('register)";
    submit() {
      this.form.post(this.route('register'), { 
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      })
    }

## User Accounts

- PCC ADMIN
// Username & Password: ceojhonpaul@gmail.com

- PCC HQ Staff
// Username & Password: hannah@gmail.com

- PCC Vet Field Staff
// Username & Password: cristine@gmail.com

- PCC Ext. Workers
// Username & Password: llc@gmail.com

## Auth and Middleware

-Middleware used for Authentication ang segmented routing (Please see "routes/web.php")
    Route Middlewares Declared in "Http/Kernel.php"
    - admin - PCC Admin
    - vetfield - PCC Vet/Field Staff
    - headquarters - PCC HQ Staff
    - extworkers - Extenstion Workers

    //Check these page for references: (Used for validating users if they are in the right route and redirecting them back to their routes if they try to go to other/restricted routes)
    - "Http/Middleware/Admin.php"
    - "Http/Middleware/HeadQuarters.php"
    - "Http/Middleware/VetField.php"
    - "Http/Middleware/ExtWorkers.php"

- For this to be appreciated, Login the "PCC ADMIN Account" listed above.
Once Logged in, you can see "http://gfusa.livestock.co/pcc_admin" or http://127.0.0.1:8000/pcc_admin

- Change the "pcc_admin" prefix to like "pcc_hq"
- You will see how it will be redirected to it's own route.

I hope this gives clarity on a general POV of the structure of Laravel+VueJS/InertiaJS



## Latest Updates (branch: health_content_fe_be | Front-End & Back-End)

Edited HealthGuide Page
- Health Systems (Flex Boxes)
- Health Content List per Health Systems (Tabled)
- Specific Health Content (Modal)
	- Edit & Update - Web Routes & Controller Methods *Updated*

Added Package
vue-zoom-on-hover
(Url: https://github.com/Intera/vue-zoom-on-hover)

CK Editor
(Url: https://ckeditor.com/docs/ckeditor5/latest/builds/guides/integration/frameworks/vuejs-v3.html#quick-start)

SweetAlert2
(Url: https://sweetalert2.github.io/)


## Latest Updates (branch: health_content_fe_be | Front-End & Back-End) - June 21, 2021

Edited HealthGuide Page
- Edit/Update function for Specific System
- Created Symptoms List (Tabled)

Added Package
vueform/multiselect
(Url: https://github.com/vueform/multiselect)


## Latest Updates (branch: ifeed-PCCP2-48) - Jul 27, 2021

Added packages for Downloading of CSV/Excel

phpoffice/phpspreadsheet
(url: https://github.com/PHPOffice/PhpSpreadsheet)

Maatwebsite/Laravel-Excel
(url: https://github.com/Maatwebsite/Laravel-Excel)

Requires the ff:

Config Update in php.ini file:
Uncomment extension=php_gd2.dll

## Latest Updates - Oct 5, 2021

install calendar
npm install v-calendar
install npm i @popperjs/core as depency for v-calendar



