<div class="card">
    <div class="card-body">
        <form id="change_password_form" action="javascript::void(0)" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsCurrentPassword" class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control"
                        aria-describedby="settingsCurrentPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    <div id="settingsCurrentPassword" class="form-text">Never share your password with
                        anyone.</div>
                    <div class="text-danger">
                        <strong>
                            <p id="error_current_password"></p>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsNewPassword" class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" aria-describedby="settingsNewPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    <div class="text-danger">
                        <strong>
                            <p id="error_password"></p>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsConfirmPassword" class="form-label">Confirm New
                        Password</label>
                    <input type="password" name="confirm_password" class="form-control"
                        aria-describedby="settingsConfirmPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    <div class="text-danger">
                        <strong>
                            <p id="error_confirm_password"></p>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="row m-t-lg">
                <div class="col">
                    <a id="button_change_password" href="javascript::void(0)" class="btn btn-primary m-t-sm">Change
                        Password</a>
                    <div class="text-success">
                        <strong>
                            <p id="change_password_status"></p><strong>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
