        </div>
    </main>

    <footer class="site-footer">
        <div class="container py-4">
            <div class="footer-card">
                <div>
                    <h4>Biblioteca Universitaria</h4>
                    <p class="mb-0">Gestion de libros, reservas y prestamos.</p>
                </div>
                <div class="footer-meta">
                    <span class="footer-badge">PHP MVC</span>
                    <span class="footer-badge">Bootstrap 5</span>
                    <span class="footer-badge">XAMPP Ready</span>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-hT9eHlo7lR84bF0GK2jspYVi5HTkXyfCoN6ZB2mT6qvHNLoSOxQ1R+0v4JH5w6j0" crossorigin="anonymous"></script>
<script>
document.querySelectorAll('.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});
</script>
</body>
</html>
