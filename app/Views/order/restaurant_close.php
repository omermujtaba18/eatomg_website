    <div class="modal fade bd-example-modal-lg" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger text-center">Sorry, We are closed right now!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <b>House of operation at <?= $restaurant['rest_name']; ?>:</b> <br><br>

                    <?php foreach ($times as $t) :
                        $time = $t['is_closed'] ? 'Closed' : ($t['is_24h_open'] ? 'Open 24 hours' : $t['start_time'] . ' - ' . $t['end_time']);
                        echo ($t['day'] . " : " . $time . '<br>');
                    endforeach; ?>
                    <br>
                    Thank you,<br>
                    <?= $business['business_name']; ?>
                </div>
            </div>
        </div>
    </div>