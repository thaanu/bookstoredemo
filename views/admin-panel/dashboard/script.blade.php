<script>
    let pgContent = document.querySelector('#pg-content');
    function loadData() {
        hfPostRequest('/cp/dashboard/data').then(response => {
            if ( response.status == 200 ) {
                pgContent.innerHTML = response.view;
            } else {
                pgContent.innerHTML = response.error;
            }
        });
    }
    loadData();
    setInterval(() => {
        loadData();
    }, 1000);
</script>