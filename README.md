# Vercel Next.js Autodeployment Functions for WordPress

A WordPress plugin that automatically triggers Vercel builds when posts or pages are published, enabling seamless integration between WordPress content management and Next.js frontend deployments.

## Overview

This plugin hooks into WordPress's `wp_insert_post` action to automatically trigger Vercel rebuilds whenever content is published. It's designed for headless WordPress setups where a Next.js frontend consumes WordPress content via the REST API.

## Features

- **Automatic Build Triggers**: Triggers Vercel builds when posts or pages are published
- **Selective Post Types**: Only triggers for 'post' and 'page' post types by default
- **Error Logging**: Logs build requests and errors for debugging
- **Lightweight**: Minimal code footprint with no external dependencies

## Installation

1. Upload `autodeployment-vercel.php` to your WordPress plugins directory (`/wp-content/plugins/`)
2. Activate the plugin through the WordPress admin panel
3. Configure the Vercel build URL (see Configuration section)

## Configuration

### Required Setup

Before using this plugin, you need to:

1. **Set up Vercel Webhook**: 
   - Go to your Vercel project dashboard
   - Navigate to Settings → Git Integration
   - Copy the webhook URL or create a custom deployment hook

2. **Update the Build URL**:
   - Edit line 9 in `autodeployment-vercel.php`
   - Replace `'?buildCache-false'` with your actual Vercel webhook URL
   - Example: `'https://api.vercel.com/v1/integrations/deploy/prj_1234567890abcdef/abcdef123456'`

### Customization

#### Modify Post Types
To trigger builds for additional post types, edit line 5:
```php
$allowed_post_types = ['post', 'page', 'product', 'custom_post_type'];
```

#### Change Build Trigger Conditions
To trigger builds on post updates (not just new posts), modify line 7:
```php
if ($post->post_status == 'publish' || $post->post_status == 'updated') {
```

## How It Works

1. **Content Publication**: When a post or page is published in WordPress
2. **Hook Trigger**: The `wp_insert_post` action fires the `auto_build_request` function
3. **Build Request**: Sends a POST request to the configured Vercel webhook URL
4. **Response Handling**: Logs the response or error to an `error` file for debugging
5. **Build Execution**: Vercel automatically rebuilds and deploys your Next.js application

## File Structure

```
vercel-nextjs-autodeployment-functions.php/
├── README.md                    # This documentation file
├── autodeployment-vercel.php    # Main plugin file
└── error                        # Log file created automatically (if needed)
```

## Logging

The plugin creates an `error` file in the plugin directory to log:
- Post types that triggered builds
- API response data
- Error messages (if any)

**Note**: Ensure the plugin directory has write permissions for logging to work properly.

## Troubleshooting

### Common Issues

1. **Builds Not Triggering**:
   - Check if the Vercel webhook URL is correctly configured
   - Verify the plugin is activated
   - Check WordPress error logs

2. **Permission Errors**:
   - Ensure the plugin directory has write permissions for logging
   - Check file permissions on the `error` file

3. **Build Failures**:
   - Verify your Vercel project configuration
   - Check Vercel deployment logs for build errors

### Debug Mode

To enable more detailed logging, modify the plugin to log additional information:
```php
$content = json_encode([
    'post_id' => $post_id,
    'post_type' => $post_type,
    'post_status' => $post->post_status,
    'response' => $body
]);
```

## Security Considerations

- **Webhook Security**: Consider adding authentication to your Vercel webhook
- **Rate Limiting**: Be aware of Vercel's API rate limits for high-volume sites
- **File Permissions**: Ensure proper file permissions for the log file

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Vercel account with configured project
- Appropriate file system permissions for logging

## License

This plugin is provided as-is for educational and development purposes. Please review and modify according to your specific requirements.

## Support

For issues related to:
- **WordPress functionality**: Check WordPress documentation
- **Vercel deployments**: Refer to Vercel documentation
- **Plugin-specific issues**: Review the code and logs for troubleshooting

---

**Important**: This plugin is designed for headless WordPress setups. Ensure your WordPress site is properly configured to work with your Next.js frontend before implementing automatic deployments.