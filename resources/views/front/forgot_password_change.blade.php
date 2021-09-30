@extends('front/layout')
@section('page_title', 'Change Password')
@section('container')

 <section id="aa-myaccount">
   <div class="container"><!-- 
     <div class="row">
       <div class="col-md-12"> -->
        <div class="aa-myaccount-area">         
            <div class="row mx-auto">
              <div class="col-md-6">
                <div class="aa-myaccount-register">                 
                 <h4>Change your Password</h4>
                 <form action="forgot_password_change_process" class="aa-login-form" id="frmUpdatePassword">

                    <label for="">New Password<span>*</span></label> 
                    <input type="password" name="password" placeholder="Password" required>
                    <div id="password_error" class="field_error"></div>

                    <button type="submit" class="aa-browse-btn" id="btnUpdatePassword">Update Password</button>  
                    @csrf                  
                  </form>
                  <div id="thank_you_msg" class="field_error"></div>
                </div>
              </div>
            </div>          
         </div><!-- 
       </div>
     </div> -->
   </div>
 </section>

@endsection