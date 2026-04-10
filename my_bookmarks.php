<style>
    .content.card {
        width: 100%; /* Ensures it takes full width */
        max-width: none; /* Removes any max-width restriction */
    }

    .container {
        width: 100%;
        max-width: none;
        padding: 0 20px; /* Adjust padding as needed */
    }

    .row {
        width: 100%;
        margin: 0 auto; /* Centers the row */
    }
    
    .search-bar {
        margin-top: 5px; /* Reduced top margin */
        margin-bottom: 15px;
    }
    .dropdown-toggle::after {
    margin-left: 0.5em;
}
</style>
<!--
<div class="container mt-3">
    <a href="./" class="btn btn-secondary btn-sm mb-3">← Back to Home</a> &emsp;&emsp;<a href="./?page=profile" class="btn btn-secondary btn-sm mb-3">← Back to Profile</a>
</div>
-->
<div class="content py-3">
    <div class="container-fluid">
        <div class="card card-outline shadow rounded-0">
            <div class="card-header rounded-0 bg-primary">
                <h4 class="card-title text-white"><i class="fas fa-bookmark"></i><b> My Bookmarks</b></h4>
            </div>
            <div class="card-body rounded-0">
                <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search bookmarks...">
                <div class="container-fluid">
                    <table class="table table-hover table-striped" id="bookmarksTable">
                        <colgroup>
                            <col width="5%">
                            <col width="10%">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr class="text-dark">
                                <th>#</th>
                                <th>Date Bookmarked</th>
                                <th>Title</th>
                                <th>Author(s)</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php 
        $i = 1;
        $qry = $conn->query("SELECT *, b.id as bookmark_id, b.created_at, a.id as archive_id, a.title, a.authors, a.type, a.document_path FROM bookmarks b 
                            JOIN archive_list a ON b.document_id = a.id 
                            WHERE b.user_id = '{$_settings->userdata('id')}'
                            ORDER BY unix_timestamp(b.created_at) ASC");

        if ($qry->num_rows > 0):
            while($row = $qry->fetch_assoc()):
    ?>
    <tr>
        <td class="text-center" style="color: black;"><?php echo $i++; ?></td>
        <td class="text-center" style="color: black;"><?php echo date("Y-m-d H:i",strtotime($row['created_at'])) ?></td>
        <td class="text-left" style="color: black;" class="bookmark-title"><?php echo ucwords($row['title']) ?></td>
        <td class="text-left" style="color: black;" class="bookmark-authors">
            <?php
            $authors = explode(';', $row['authors']); 
            echo ucwords($authors[0]); 
            if (count($authors) > 1): 
            ?>
            <span class="more-members" style="display: none;">
                <?php echo ', ' . ucwords(implode(', ', array_slice($authors, 1))); ?>
            </span>
            <button class="btn btn-link btn-sm show-more">Show More</button>
            <?php endif; ?>
        </td>
        <td class="text-center">
            <?php
                switch($row['type']){
                    case '1':
                        echo "<span class='badge badge-success badge-pill'>Thesis</span>";
                        break;
                    case '2':
                        echo "<span class='badge badge-secondary badge-pill'>Feasibility Study</span>";
                        break;
                }
            ?>
        </td>
        <td align="center">
            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                Action
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item view-gaps" href="#" data-gaps="<?= htmlspecialchars($row['gaps']); ?>">
                    <span class="fa fa-search text-gray"></span> View Gaps
                </a>
            </div>
        </td>
    </tr>
    <?php 
            endwhile;
        else: 
    ?>
    <tr>
        <td colspan="6" class="text-center text-muted py-5">
            <div>
                <p class="mb-2">📭 You have no bookmarks yet.</p>
                <a href="./?page=archives" class="btn btn-primary btn-sm">Browse Archives</a>
            </div>
        </td>
    </tr>
    <?php endif; ?>
</tbody>
<div class="modal fade" id="gapViewer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Identified Gaps in Research and AI Suggestions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark">
                <p id="gapContent" style="text-align: justify;">Loading gaps...</p>
            </div>
        </div>
    </div>
</div>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>-->
<script>
    $(document).ready(function(){
        $('.show-more').click(function(){
            $(this).siblings('.more-members').toggle();
            $(this).text($(this).text() === "Show More" ? "Show Less" : "Show More");
        });
    
        $(document).on('click', '.view-gaps', function(e){
    e.preventDefault();
    let gapsText = $(this).data('gaps');

    // Optional: Close dropdown manually to avoid second click issue
    $('.dropdown-menu').removeClass('show');

    // Show content
    if (gapsText && gapsText.trim() !== "") {
        $('#gapContent').html(gapsText.replace(/\n/g, "<br>"));
    } else {
        $('#gapContent').html("<em>No identified gaps found.</em>");
    }

    // Delay to ensure dropdown is fully closed before showing modal
    setTimeout(function() {
        $('#gapViewer').modal('show');
    }, 100);
});
        $(document).on('click', '.close', function() {
            $('#gapViewer').modal('hide');
        });

        $('#searchInput').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#bookmarksTable tbody tr').each(function() {
                if ($(this).text().toLowerCase().indexOf(value) > -1 || value === "") {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>