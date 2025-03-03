<p  align="center"><img  src="https://github.com/mulky-sulaiman/fortify-ui-livewire/raw/master/fortify-ui-image.png"  width="400"></p>

# Introduction

> [!NOTE]
> For Laravel 10 and below, use branch v1.x

**FortifyUILivewire** is an unopinionated authentication starter, powered by [*Laravel Fortify*](https://github.com/laravel/fortify). It is completely unstyled -- on purpose -- and only includes a minimal amount of markup to get your project running quickly. This package can be used to start your project, or you can use the [*FortifyUILivewire Preset Template*](https://github.com/MulkySulaiman/fortify-ui-preset) which allows you to create your own preset that you can install with **FortifyUILivewire**.


### In a nutshell...
**FortifyUILivewire** automates the base installation and configuration of *Laravel Fortify*, it includes the features that *Laravel Fortify* recommends implementing yourself and it provides the scaffolding for you to build your own UI around it. Hence, Fortify + UI.

---

- [Introduction](#introduction)
    - [In a nutshell...](#in-a-nutshell)
  - [Installation](#installation)
  - [Configuration](#configuration)
  - [Features](#features)
    - [Email Verification](#email-verification)
    - [Password Confirmation](#password-confirmation)
    - [Two-Factor Authentication](#two-factor-authentication)
    - [Update User Password/Profile](#update-user-passwordprofile)
  - [FortifyUILivewire Presets](#fortifyuilivewire-presets)
    - [Community Presets](#community-presets)
  - [License](#license)

<a name="installation"></a>
## Installation

To get started, you'll need to install **FortifyUILivewire** using Composer. This will install *Laravel Fortify* as well so, please make sure you **do not** have it installed, already.

```bash
composer require MulkySulaiman/fortify-ui
```

Next, you'll need to run the install command:

```bash
php artisan fortify:ui
```

This command will publish **FortifyUILivewire's** views, add the `home` route to `web.php` and add the **FortifyUILivewire** service provider to your `app/Providers` directory. This will also publish the service provider and config file for *Laravel Fortify*. Lastly, it will register both service providers in the `app.php` config file, under the providers array.

That's it, you're all setup! For advanced setup and configuration options, keep reading!

<a name="configuration"></a>
## Configuration

The **FortifyUILivewire** service provider registers the views for all of the authentication features. If you'd rather **not** include the **FortifyUILivewire** service provider, you can skip generating it by using the `--skip-provider` flag.

```bash
php artisan fortify:ui --skip-provider
```

Then, you can add this to your `AppServiceProvider` or `FortifyServiceProvider`, in the `boot()` method.

```php
Fortify::loginView(function () {
    return view('auth.login');
});

Fortify::registerView(function () {
    return view('auth.register');
});

Fortify::requestPasswordResetLinkView(function () {
    return view('auth.forgot-password');
});

Fortify::resetPasswordView(function ($request) {
    return view('auth.reset-password', ['request' => $request]);
});

// Fortify::verifyEmailView(function () {
//     return view('auth.verify-email');
// });

// Fortify::confirmPasswordView(function () {
//     return view('auth.confirm-password');
// });

// Fortify::twoFactorChallengeView(function () {
//     return view('auth.two-factor-challenge');
// });
```

To register all views at once, you can use this instead:

```php
Fortify::viewPrefix('auth.');
```

Now, you should have all of the registered views required by *Laravel Fortify*, including basic layout and home views, as well as optional password confirmation, email verification and two-factor authentication views.

<a name="features"></a>
## Features

By default, **FortifyUILivewire** is setup to handle the basic authentication functions (Login, Register, Password Reset) provided by *Laravel Fortify*.

<a name="features-email-verification"></a>
### Email Verification
To enable the email verification feature, you'll need to visit the **FortifyUILivewire** service provider and uncomment `Fortify::verifyEmailView()`, to register the view. Then, go to the `fortify.php` config file and make sure `Features::emailVerification()` is uncommented. Next, you'll want to update your `User` model to include the following:

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    ...
```

This allows you to attach the `verified` middleware to any of your routes, which is handled by the `verify.blade.php` file.

[More info about this can be found here.](https://github.com/laravel/fortify/blob/1.x/README.md#email-verification)

<a name="features-password-confirmation"></a>
### Password Confirmation
To enable the password confirmation feature, you'll need to visit the **FortifyUILivewire** service provider and uncomment `Fortify::confirmPasswordView()`, to register the view. This allows you to attach the `password.confirm` middleware to any of your routes, which is handled by the `password-confirm.blade.php` file.

<a name="features-two-factor-auth"></a>
### Two-Factor Authentication
To enable the two-factor authentication feature, you'll need to visit the **FortifyUILivewire** service provider and uncomment `Fortify::twoFactorChallengeView()`, to register the view. Then, go to the `fortify.php` config file and make sure `Features::twoFactorAuthentication` is uncommented. Next, you'll want to update your `User` model to include the following:

```php
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    ...
```

That's it! Now, you can log into your application and enable or disable two-factor authentication.

<a name="features-password-profile"></a>
### Update User Password/Profile
To enable the ability to update user passwords and/or profile information, go to the `fortify.php` config file and make sure these features are uncommented:

```php
Features::updateProfileInformation(),
Features::updatePasswords(),
```

<a name="presets"></a>
## FortifyUILivewire Presets

**FortifyUILivewire** encourges make your own presets, with your favorite frontend libraries and frameworks. To get started, visit the [*FortifyUILivewire Preset Template*](https://github.com/MulkySulaiman/fortify-ui-preset) repository, and click the "Use Template" button.

### Community Presets

Presets for v1.x can be found in that branch.

## License

**FortifyUILivewire** is open-sourced software licensed under the [MIT license](LICENSE.md).
