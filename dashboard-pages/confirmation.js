var confirmed = confirm('The remaining amount will be deducted from your savings. Do you want to continue?');
if (confirmed) {
    window.location.href = window.location.href + '?confirmed=true';
} else {
    window.location.href = 'dashboard.php';
}