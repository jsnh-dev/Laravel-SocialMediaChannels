<small>Author: Janic Scheinhardt (info@jsnh.dev)</small><br>
<small>Created: 2024-08-31</small><br>
<small>Last update: 2024-09-08</small>

<br> 

# Permissions

After installing Laravel, you may need to configure some permissions.
Directories within the <b>storage</b> and the <b>bootstrap/cache</b> directories should be writable by your web server or Laravel will not run.

> sudo chgrp www-data storage/ bootstrap/cache/ -R<br>
> sudo chmod g+ws storage/ bootstrap/cache/ -R

<br> 

# Project Settings

Install Vendor dependencies via:

> composer install

Install NPM dependencies via:

> npm install

Compile your fresh scaffolding by running:

> npm run dev

Create the <b>.env</b>-file and generate an app key with:

> php artisan key:generate

Define your database. 

```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Then use the migration script to migrate all tables.

> php artisan migrate<br>

Now, your project should already be accessible in the browser.  

<br> 

# API Settings

To fill your website with data, you need to setup the desired APIs.

<br> 

### X

Create an account on the Developer Platform for X (https://developer.x.com). Navigate to the Developer Portal to create a new project. Get your API keys and define them in the <b>.env</b>-file.

```
X_API_KEY=
X_API_KEY_SECRET=
X_ACCESS_TOKEN=
X_ACCESS_TOKEN_SECRET=
```

For embedding your timeline you need to define your username.

```
X_USERNAME=
```

To initialize your X data run:

> php artisan app:x

<br> 

### Twitch

Create an account for Twitch Developers (https://dev.twitch.tv/) and inside the Developer Console (https://dev.twitch.tv/console) create your application. Get a Client ID and a Client Secret to create a Bearer Token and define them in your <b>.env</b>-file.

```
TWITCH_CLIENT_ID=
TWITCH_BEARER_TOKEN=
```

Further, you need to define your username for the API calls and for embedding the live stream and chat.

```
TWITCH_USERNAME=
```

To initialize your Twitch data run:

> php artisan app:twitch

<br> 

### YouTube

Sign up for YouTube API in the Google Developer Console (https://console.cloud.google.com). Create a project, get your API key and define it in your <b>.env</b>-file.

```
YOUTUBE_API_KEY=
```

Set your YouTube Channel ID.

```
YOUTUBE_ID=
```

To initialize your YouTube data run:

> php artisan app:youtube

<br> 

### Instagram

For the Instagram API you need to sign up on Meta for Developers (https://developers.facebook.com/) and create your app. You also need a business Facebook and Instagram account to use the API. Once you have generated your access token, you can retrieve your Facebook ID via an API call (https://graph.facebook.com/v20.0/me/accounts?access_token={{accessToken}}). You can then use this ID to retrieve your Instagram Business Account ID (https://graph.facebook.com/v20.0/{{facebookID}}?access_token={{accessToken}}&fields=instagram_business_account). Save your Instagram ID and your access token to your <b>.env</b>-file.

```
INSTAGRAM_PROFILE_ID=
INSTAGRAM_ACCESS_TOKEN=
```

To initialize your Instagram data run:

> php artisan app:instagram

<br> 

### Bluesky

To use the Bluesky API you only need a normal user account (https://bsky.app/). Add your full handle (example.bsky.social) as identifier and your password (or an app password if you have set one) to your <b>.env</b>-file.

```
BLUESKY_IDENTIFIER=
BLUESKY_PASSWORD=
```

To initialize your Bluesky data run:

> php artisan app:bluesky

Now, all your social media channels should be visible on the website.

<br> 

# Job Scheduler

The data will be regularly requested by API in scheduled jobs defined in <b>routes/console.php</b>. Don't forget to enable jobs in your crontab.

```
* * * * * cd /PATH_TO_YOUR_WORKSPACE/Laravel-SocialMediaChannels/ && php artisan schedule:run >> /dev/null 2>&1
```