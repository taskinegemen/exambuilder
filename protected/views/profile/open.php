<!--<div class="container col-lg-4 col-lg-offset-4">
	<div class="row">
			<br><br>
		 <div class="panel panel-info" style="padding: 20px;">
		 	<form class="form-horizontal">
                <div class="form-group">
                	<div class="col-lg-offset-1">
	                    <label for="profile_username" class="control-label col-xs-3">User Name</label>
	                    <div class="col-xs-6">
	                        <input type="text" class="form-control" id="profile_username" value="<?php echo $user->username;?>">
	                    </div>
                    </div>
                </div>
                <div class="form-group">
                	<div class="col-lg-offset-1">
                		<label for="profile_password" class="control-label col-xs-3">Password</label>
	                    <div class="col-xs-6">
	                        <input type="password" class="form-control" id="profile_password" placeholder="Enter your password">
	                    </div>
	                </div>
                </div>
                <div class="form-group">
                	<div class="col-lg-offset-1">
	                	<label for="profile_password_again" class="control-label col-xs-3">Password Again</label>
	                    <div class="col-xs-6">
	                        <input type="password" class="form-control" id="profile_password_again" placeholder="Re-renter your password">
	                    </div>
	                </div>
                </div>
                <div class="form-group">
                	<div class="col-lg-offset-8">
				        <div class="col-lg-12">
				            <button type="submit" class="btn btn-primary">Save</button>
				        </div>
					</div>
				</div>
            </form>
		 </div>
	</div>
</div>-->
<div class="container col-lg-4 col-lg-offset-4">
    <form class="form-horizontal">
        <div class="form-group">
            <label for="profile_username" class="control-label col-xs-2">Email</label>
            <div class="col-xs-10">
                <input type="email" class="form-control" id="profile_username" placeholder="User name" value="<?php echo $user->username;?>">
            </div>
        </div>
        <div class="form-group">
            <label for="profile_password" class="control-label col-xs-2">Password</label>
            <div class="col-xs-10">
                <input type="password" class="form-control" id="profile_password" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="profile_password_again" class="control-label col-xs-2">Re-Password</label>
            <div class="col-xs-10">
                <input type="password" class="form-control" id="profile_password_again" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <button type="button" class="btn btn-primary" id="profile_save">Save</button>
            </div>
        </div>
    </form>
 </div>