<?php
/**
 * Auto-login plugin for Adminer.
 * Reads credentials from environment variables and fill the login form
 * Optional JS auto-submit, so the user never sees the form.
 */
class Autologin extends Adminer\Plugin {

    public function loginForm() {
        $driver   = getenv('ADMINER_DEFAULT_DRIVER')   ?: 'server';
        $username = getenv('ADMINER_DEFAULT_USERNAME') ?: '';
        $password = getenv('ADMINER_DEFAULT_PASSWORD') ?: '';
        $db       = getenv('ADMINER_DEFAULT_DB')       ?: '';

        // Only run if the form hasn't already been submitted (e.g. after a failed login)
        if (empty($_GET['driver']) && empty($_GET['username']) && empty($_GET['db'])) {
            ?>
            <script<?= Adminer\nonce(); ?>>
            document.addEventListener("DOMContentLoaded", function() {
                var dr = document.querySelector('option[value="<?= htmlspecialchars($driver) ?>"]');
                if (dr) dr.selected = true;

                <?php if ($username): ?>
                var u = document.querySelector("input[name='auth[username]']");
                if (u && u.value.trim() === "") u.value = "<?= htmlspecialchars($username) ?>";
                <?php endif; ?>

                <?php if ($password !== ''): ?>
                var p = document.querySelector("input[name='auth[password]']");
                if (p && p.value.trim() === "") p.value = "<?= htmlspecialchars($password) ?>";
                <?php endif; ?>

                <?php if ($db): ?>
                var d = document.querySelector("input[name='auth[db]']");
                if (d && d.value.trim() === "") d.value = "<?= htmlspecialchars($db) ?>";
                <?php endif; ?>

                // var btn = document.querySelector('input[type="submit"]:not(.hidden)');
                // if (btn) btn.click();
            });
            </script>
            <?php
        }
        return null;
    }
}

return new Autologin;
