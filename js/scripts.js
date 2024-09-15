document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('wc-advanced-filter-form').addEventListener('submit', function (event) {
        // Get all form fields
        var inputs = this.querySelectorAll('input, select');

        // Loop through all form fields and remove empty ones
        inputs.forEach(function (input) {
            if (!input.value) {
                input.name = ''; // Remove the name attribute to exclude it from the query string
            }
        });
    });

});
