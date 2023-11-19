<?php
function generateBSPagination($url, $currentPage, $totalPages, $clickableClass = '')
{
    $gap = 6;
    $lastPage = $totalPages;
    $startAt = (($currentPage - $gap) > 0 ?  $currentPage - $gap : 1);
    $finishAt = (($currentPage + $gap) < $lastPage ?  $currentPage + $gap : $lastPage);

    // Show pagination if total records are more than 1
    if ($totalPages > 1) {
        echo '<ul class="pagination">';

        if ($currentPage > 1) {
            echo '<li class="page-item"><a class="page-link ' . $clickableClass . ' " href="' . $url . '/1">First</a></li>';
            echo '<li class="page-item"><a class="page-link ' . $clickableClass . ' " href="' . $url . '/' . ($currentPage - 1) . '">Previous</a></li>';
        }

        for ($p = $startAt; $p <= $finishAt; $p++) {
            echo '<li class="page-item ' . ($p == $currentPage ? ' active ' : '') . ' " ><a class="page-link ' . $clickableClass . ' " href="' . $url . '/' . $p . '">' . $p . '</a></li>';
        }

        if ($currentPage != $totalPages) {
            echo '<li class="page-item"><a class="page-link ' . $clickableClass . ' " href="' . $url . '/' . ($currentPage + 1) . '">Next</a></li>';
            echo '<li class="page-item"><a class="page-link ' . $clickableClass . ' " href="' . $url . '/' . $totalPages . '">Last</a></li>';
        }

        echo '</ul>';
    }

    echo '<p>Page ' . $currentPage . ' of ' . $totalPages . '</p>';
}

function getInitAvartar(object $user)
{
    if (!is_object($user)) {
        throw new Exception('Please provide an object');
    }

    // Handle image
    if (isset($user->image) && !empty($user->image)) {
        echo '<img src="' . assets('assets/images/users/user-3.jpg') . '" alt="user-image" class="rounded-circle">';
    }
    // Otherwise show initials
    else {

        // Get the initial from display name
        $ex = explode('.', $user->display_name);

        echo '<div class="avatar-md"><span class="avatar-title bg-soft-secondary text-secondary font-20 rounded-circle">';
        echo strtoupper($ex[0][0]);
        if (isset($ex[1])) {
            echo strtoupper($ex[1][0]);
        }
        echo '</span></div>';
    }
}


function titleWithCount($title, $count)
{
    if ($count == 1) {
        return "$count $title";
    } else if ($count > 1) {
        return "$count $title" . "s";
    } else {
        return "No records found";
    }
}




// Functions below are based on this project
function deviceValues($value, $unit)
{
    $value = ($value ? number_format($value, 0) : NULL);
    switch ($unit) {
        case 'humidity':
            return ( $value ? $value . '%' : 'NA' );
            break;
        case 'temp':
            return ( $value ? $value . '&deg;C' : 'NA' );
            break;
        case 'precipitation':
            return ( $value ? $value . '%' : 'NA' );
            break;
        case 'wind':
            return ( $value ? $value . 'km/h' : 'NA' );
            break;
        case 'cloudiness':
            return ( $value ? $value : 'NA' );
            break;
        default:
            return 'NA';
    }
}
