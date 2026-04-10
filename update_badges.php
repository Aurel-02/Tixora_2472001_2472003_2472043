<?php
$files = glob(resource_path('views/*.blade.php'));
$files = array_filter($files, function($f) {
    return !in_array(basename($f), ['landing.blade.php', 'login.blade.php', 'signup.blade.php', 'adminlogin.blade.php', 'select-seat.blade.php', 'face-scan.blade.php']);
});

foreach ($files as $file) {
    $content = file_get_contents($file);
    $changed = false;
    
    // Replace admin notifikasi link
    $adminPattern = '/(<a href="[^"]*admin\/notifikasi"[^>]*>)\s*(<i class="ph ph-bell[^"]*"><\/i>)\s*(<span class="sidebar-text">.*?<\/span>)\s*(<\/a>)/s';
    if (preg_match($adminPattern, $content)) {
        $content = preg_replace($adminPattern, '$1' . "\n" . '                            $2' . "\n" . '                            $3' . "\n" . '                            @if(isset($unreadCount) && $unreadCount > 0)' . "\n" . '                            <span style="position: absolute; right: 15px; margin-top: 12px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $unreadCount }}</span>' . "\n" . '                            @endif' . "\n" . '                        $4', $content);
        $changed = true;
    }
    
    // Replace organizer notifikasi link
    $orgPattern = '/(<a href="[^"]*organizer\/notifications"[^>]*>)\s*(<i class="ph ph-bell[^"]*"><\/i>)\s*(<span class="sidebar-text">.*?<\/span>)\s*(<\/a>)/s';
    if (preg_match($orgPattern, $content)) {
        $content = preg_replace($orgPattern, '$1' . "\n" . '                            $2' . "\n" . '                            $3' . "\n" . '                            @if(isset($unreadCount) && $unreadCount > 0)' . "\n" . '                            <span style="position: absolute; right: 15px; margin-top: 12px; background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $unreadCount }}</span>' . "\n" . '                            @endif' . "\n" . '                        $4', $content);
        $changed = true;
    }
    
    // Replace buyer notifikasi link
    $buyPattern = '/(<a href="[^"]*notifications"[^>]*>)\s*(<i class="ph ph-bell[^"]*"><\/i>)\s*(<span class="sidebar-text">.*?<\/span>)\s*(<\/a>)/s';
    $content2 = $content;
    if (preg_match($buyPattern, $content)) {
        // Just generic replace for any notifications link that hasn't been caught yet
        $content = preg_replace('/(<a(?:(?!position: relative).)*?href="[^"]*notifications"[^>]*>)/', '$1 style="position: relative;"', $content);
        $changed = true;
    }
    
    // Ensure all <a> tags that got replaced have position: relative 
    $content = str_replace('<a href="{{ route(\'admin.notifications\') }}" class="sidebar-item ', '<a style="position: relative;" href="{{ route(\'admin.notifications\') }}" class="sidebar-item ', $content);
    $content = str_replace('<a href="{{ route(\'organizer.notifications\') }}" class="sidebar-item', '<a style="position: relative;" href="{{ route(\'organizer.notifications\') }}" class="sidebar-item', $content);
    
    if ($changed || $content !== $content2) {
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    }
}
?>
