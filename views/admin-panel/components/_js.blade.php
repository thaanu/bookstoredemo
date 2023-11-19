<script src="{{ assets('assets/js/vendor.min.js') }}"></script>
<script src="{{ assets('assets/js/app.min.js') }}"></script>
<script src="{{ assets('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
<script src="{{ assets('assets/libs/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="{{ assets('assets/libs/mohithg-switchery/switchery.min.js') }}"></script>
<script src="{{ assets('assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ assets('assets/libs/hopscotch/js/hopscotch.min.js') }}"></script>
<script src="{{ assets('assets/libs/mohithg-switchery/switchery.min.js') }}"></script>


<script>
    $(document).ready(function() {
        const tour = <?= Heliumframework\Session::get('user')['tour']; ?>;
        if (tour == true || tour == null) {

            hopscotch.startTour({
                id: "my-intro-2",
                onEnd: () => {
                    // todo: set the flag as tour completed in the server. for now marking locally
                    hfPostRequest('/cp/profile/ajax/tour-done').then(response => {
                        if ( response.status == 200 ) {
                            hf_success_toast(response.textMessage);
                        } else {
                            hf_error_toast(response.error);
                        }
                    });
                },
                steps: [{
                        target: "nav-profile",
                        title: "Profile",
                        content: "This is everything about you",
                        placement: "bottom",
                        yOffset: -10,
                        xOffset: -100,
                        arrowOffset: "center"
                    },
                    {
                        target: "nav-dashboard",
                        title: "Dashboard",
                        content: "Here you can view status of all the active devices and sensors",
                        placement: "right",
                        yOffset: -10,
                        xOffset: -100
                    },
                    {
                        target: "nav-devices",
                        title: "Devices",
                        content: "Manage all the devices",
                        placement: "right",
                        yOffset: -10,
                        xOffset: -100
                    },
                    {
                        target: "nav-locations",
                        title: "Locations",
                        content: "Manage all locations",
                        placement: "right",
                        yOffset: -10,
                        xOffset: -100
                    },
                    {
                        target: "nav-users",
                        title: "Users",
                        content: "Manage system users (Admin Function)",
                        placement: "right",
                        yOffset: -10,
                        xOffset: -100
                    },
                    {
                        target: "nav-groups",
                        title: "Groups",
                        content: "Manage user groups (Admin Function)",
                        placement: "right",
                        yOffset: -10,
                        xOffset: -100
                    },
                    {
                        target: "nav-roles",
                        title: "Roles",
                        content: "Manage group foles (Admin Function)",
                        placement: "right",
                        yOffset: -10,
                        xOffset: -100
                    }
                ],
                showPrevButton: true
            });
        }
    });
</script>
