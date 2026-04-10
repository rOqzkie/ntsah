<?php
echo "<pre>";

// Change file permissions
echo shell_exec("chmod -R 755 uploads/pdf/");
echo shell_exec("chmod -R 644 uploads/pdf/*");

// Change ownership (might not work if Hostinger restricts it)
echo shell_exec("chown -R nobody:nogroup uploads/pdf/");

echo "Permissions updated!";
echo "</pre>";
?>