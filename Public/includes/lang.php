<?php
/**
 * Translation helper for Sportx
 * Handles mapping of internal database keys and UI strings to English labels.
 */

$translations = [
    // --- Database Enums & Values ---
    'db_roles' => [
        'usuario' => 'User',
        'admin' => 'Administrator',
    ],
    'db_status' => [
        'activo' => 'Active',
        'suspendido' => 'Suspended',
    ],
    'db_units' => [
        'por_hora' => 'Per Hour',
        'por_clase' => 'Per Class',
        'mensual' => 'Monthly',
        'uso de cancha' => 'Court Use',
    ],
    'db_days' => [
        'lunes' => 'Monday',
        'martes' => 'Tuesday',
        'miercoles' => 'Wednesday',
        'jueves' => 'Thursday',
        'viernes' => 'Friday',
        'sabado' => 'Saturday',
        'domingo' => 'Sunday',
    ],

    // --- General UI ---
    'ui_general' => [
        'back_to_menu' => 'Back to Menu',
        'back' => 'Back',
        'view_location' => 'View Location',
        'phone' => 'Phone',
        'location' => 'Location',
        'description' => 'Description',
        'general_info' => 'General Information',
        'days' => 'Days',
        'info_unavailable' => 'Information not available',
        'location_not_specified' => 'Location not specified',
        'phone_not_available' => 'Phone not available',
        'no_results' => 'No sports centers found matching your search',
    ],

    // --- Academy Details ---
    'ui_academy' => [
        'gallery' => 'Gallery',
        'academy_info' => 'Academy Information',
        'check_prices' => 'Check updated prices directly with administration',
        'reviews' => 'Reviews',
        'no_reviews' => 'No reviews yet. Be the first to rate!',
        'leave_review' => 'Leave your review',
        'review_prompt' => 'Have you visited this academy? Share your experience with other users.',
        'rating' => 'Rating',
        'your_review' => 'Your review',
        'review_placeholder' => 'Write your experience...',
        'post_review' => 'Post review',
        'view_all_reviews' => 'View all reviews',
    ],

    // --- Auth & Profile ---
    'ui_auth' => [
        'login' => 'Login',
        'logout' => 'Logout',
        'register' => 'Register',
        'email' => 'Email',
        'password' => 'Password',
        'welcome' => 'Welcome',
        'no_favorites' => 'You haven\'t added any academies to your favorites yet.',
        'invalid_id' => 'Invalid center ID',
    ],

    // --- JS Alerts ---
    'ui_errors' => [
        'form_not_found' => 'Login form not found.',
        'required_fields' => 'Email and password are required.',
        'invalid_email' => 'Please enter a valid email address.',
        'logging_in' => 'Logging in...',
        'logout_error' => 'An error occurred while logging out.',
    ],
];

/**
 * Translate a key to English.
 * Supports nested categories.
 * @param string $key The translation key (e.g., 'ui_general.back').
 * @return string The translated label or the original key if not found.
 */
function __t($key) {
    global $translations;

    // Handle dot notation for categories: 'category.key'
    if (strpos($key, '.') !== false) {
        list($category, $subKey) = explode('.', $key, 2);
        if (isset($translations[$category][$subKey])) {
            return $translations[$category][$subKey];
        }
    }

    // Fallback for old flat keys (Database values)
    // We check all categories for the key to maintain backward compatibility
    foreach ($translations as $category) {
        if (is_array($category) && isset($category[$key])) {
            return $category[$key];
        }
    }

    return $key;
}
?>
