</main>

<footer class="footer">
    <div class="footer-container">
        <span class="footer-logo">⚡ QCM<strong>Tech</strong></span>
        <span class="footer-copy">&copy; <?= date('Y') ?> — Projet Web Techno</span>
    </div>
</footer>

<?php if (isset($extra_js)): ?>
    <script src="<?= $extra_js ?>"></script>
<?php endif; ?>
<script src="/assets/js/main.js"></script>
</body>
</html>