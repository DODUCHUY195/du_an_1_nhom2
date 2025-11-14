<!doctype html>
<html>
<head><meta charset="utf-8"><title>DU_AN_11</title></head>
<body>
<nav><a href="/tours">Tours</a> | <a href="/bookings">Bookings</a> | <?php if(isset($_SESSION['user'])): ?><a href="/logout">Logout</a><?php else: ?><a href="/login">Login</a><?php endif; ?></nav>
<?php if(isset($_SESSION['flash'])): ?><div class="flash"><?php echo htmlspecialchars($_SESSION['flash']['msg']); unset($_SESSION['flash']); ?></div><?php endif; ?>
<hr>
