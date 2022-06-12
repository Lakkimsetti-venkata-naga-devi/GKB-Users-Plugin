<div class="container">
    <div class="flex-row register_form">
        <div class="info">User has been Added. </div>
        <h3>Please Fill the details to add the User.</h3>
        <div class="register_form_wrap">
            <form id="gkb_create_users" name="gkb_create_users" method="post">
                <div class="form-row">
                    <div class="form-col">
                        <label>First Name: </label>
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" autocomplete="off" required>
                    </div>
                    <div class="form-col">
                        <label>Last Name: </label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-textarea">
                    <label>Email Address: </label>
                    <input type="email" name="email" id="email" placeholder="Email Address" autocomplete="off" required>
                </div>
                <div class="form-textarea">
                    <label id="hobby_text">Hobbies: </label>
                    <input type="checkbox" name="hobby" value="TV" id="TV">
                    <label for="TV">TV</label>
                    <input type="checkbox" name="hobby" value="Reading" id="Reading">
                    <label for="Reading">Reading</label>
                    <input type="checkbox" name="hobby" value="Coding" id="Coding">
                    <label for="Coding">Coding</label>
                    <input type="checkbox" name="hobby" value="Skiing" id="Skiing">
                    <label for="Skiing">Skiing</label>
                </div>
                <div class="form-textarea">
                    <label id="gen_text">Gender: </label>
                    <input type="radio" name="gender" value="male" id="male"><label for="male">Male</label>
                    <input type="radio" name="gender" value="female" id="female"><label for="female">Female</label>
                </div>
                <div class="form-textarea">
                    <label for="prof_pic">Upload picture</label>  
                    <input id="prof_pic" class="meta_upload" name="prof_pic" type="button" value="Upload" style="width: auto;" />
                </div>
                <input type="submit" class="button" value="Submit">
                <input type="reset" class="button" value="Cancel" id="reset">
            </form>
        </div>
    </div>
</div>
