document.addEventListener('DOMContentLoaded', function() {

    const navLinks = document.querySelectorAll('a[data-nav]');

    navLinks.forEach(link => {

        link.addEventListener('click', function(e) {

            e.preventDefault();

            fetch(this.href, {
                headers: {
                    'X-Navigation': 'header-nav',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => console.error('Navigation error:', error));

        });

    });

});