<script>

    function fetchData() {
        hfPostRequest('/cp/cronjobs/ajax/fetch-data').then(response => {
            console.log(response);
            if ( response.status == 200 ) {
                document.querySelector('#jobs-container').innerHTML = response.view;
            } else {
                hf_error_toast(response.error);
            }
        });
    }

    fetchData();

    setInterval(fetchData, 10000);

</script>