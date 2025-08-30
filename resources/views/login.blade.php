<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>{{settings()->get('app_title')}}</title>
      {{-- /*
      <link rel="stylesheet" type="text/css" href="{{mix('css/app.css')}}">
      */ --}}
      <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
      <link rel="icon" href="/favicon.ico" type="image/x-icon">
   </head>
   <body>
      <div class="Main-container">
      <div class="container-login">
         <div class="wrap-login">
            <div class="login-pic">
               <a href="{{url('/')}}">
                  <img src="{{url('/')}}/images/erp_1.png" alt="IMG">
               </a>
            </div>
            <form action="/login" method="POST" class="login-form">
               <input type="hidden" name="_token" value="{{csrf_token()}}">
               <div class="col-md-2" style="background: #ffffff87;padding: 10px 0 5px 45px;">
                  @if(settings()->get('header-html'))
                     <a href="{{url('/')}}">
                        <image-upload-preview class="login-logo" width="220" height="50">
                           {!! File::get(settings()->get('header-html')) !!}
                        </image-upload-preview>
                     </a>
                  @endif
                  <br>
                  <div class="wrap-input">
                     <input type="text" class="input" name="email" id="username" placeholder="Email" required>
                     <span class="focus-input"></span>
                     <span class="symbol-input">
                     <i class="fa fa-envelope" aria-hidden="true"></i>
                     </span>
                     @if($errors->has('email'))
                     <small class="error-control">{{$errors->first('email')}}</small>
                     @endif
                  </div>
                  <div class="wrap-input">
                     <input type="password" name="password" id="password"  class="input"  placeholder="Password" required>
                     <span class="focus-input"></span>
                     <span class="symbol-input">
                     <i class="fa fa-lock" aria-hidden="true"></i>
                     </span>
                     @if($errors->has('password'))
                     <small class="error-control">{{$errors->first('password')}}</small>
                     @endif
                  </div>
                  <div class="login-form-btn-container">
                     <input type="submit" value="LOGIN" class="login-form-btn btn btn-primary">
                  </div>
            </form>
            </div>
         </div>
      </div>
   </body>
</html>
<script type="text/javascript">
   document.getElementById("admin").addEventListener('click', function () {
       var username = document.getElementById('username');
       username.value = 'kabbouchi_erp@outlook.com';
   
       var password = document.getElementById('password');
       password.value = 'qwerty123';
   });
   
   document.getElementById("sales").addEventListener('click', function () {
       var username = document.getElementById('username');
       username.value = 'info@perceiveagency.me';
   
       var password = document.getElementById('password');
       password.value = 'qwerty123';
   });
   
   document.getElementById("stock").addEventListener('click', function () {
       var username = document.getElementById('username');
       username.value = 'info@perceiveagency.me';
   
       var password = document.getElementById('password');
       password.value = 'qwerty123';
   });
   
   document.getElementById("accounting").addEventListener('click', function () {
       var username = document.getElementById('username');
       username.value = 'info@perceiveagency.me';
   
       var password = document.getElementById('password');
       password.value = 'qwerty123';
   });
   
</script>
<style>
   .login-logo > img {
        float: none !important;
        margin-top: 0 !important;
        padding: 0px !important;
        width: 50% !important;
        margin: 0 15%;
    }
   .login-page {
   width: 385px !important;
   }
   .btn-login{
   margin: 8px 0;
   }
   .btn-login-title {
   color: #484746;
   line-height: 13px;
   font-size: 13px;
   height: 32px;
   padding: 8px;
   width: 100%;
   display: block;
   }
</style>
<style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');
   /* coding With Nick */
   *{
   margin: 0px;
   padding: 0px;
   box-sizing: border-box;
   }
   body, html{
   height: 100%;
   font-family: 'Poppins',sans-serif;
   font-weight: 400;
   }
   .Main-container{
   width: 100%;
   margin: 0 auto;
   }
   .container-login{
   width: 100%;
   min-height: 100vh;
   display: flex;
   flex-wrap: wrap;
   justify-content: center;
   align-items: center;
   padding: 15px;
   background: #9053c7;
   /* background: linear-gradient(-135deg, #c850c0, #4158d0); */
   background: linear-gradient(-105deg, #c8507b, #03a9f4);
   }
   .wrap-login{
   width: 960px;
   /* background: #fff; */
   background: #ffffff78;
   border-radius: 10px;
   overflow: hidden;
   display: flex;
   flex-wrap: wrap;
   justify-content: space-between;
   /* padding: 177px 130px 33px 95px; */
   padding: 50px 130px 50px 95px;
   }
   .login-pic{
   width: 340px;
   }
   .login-pic img{
   max-width: 100%;
   }
   .login-form{
   /* width: 290px; */
   width: 310px;
   margin: 0 -30px 0px 0;
   }
   .login-form-title{
   font-family: 'poppins', sans-serif;
   font-size: 24px;
   color: #333333;
   line-height: 1.2;
   text-align: center;
   font-weight: 700;
   width: 100%;
   display: block;
   padding-bottom: 54px;
   }
   .wrap-input{
   position: relative;
   width: 100%;
   z-index: 1;
   margin-bottom: 10px;
   }
   .input{
   font-family: 'Poppins' , sans-serif;
   font-size: 15px;
   font-weight: 500;
   line-height: 1.5;
   color: #666666;
   outline: none;
   border: none;
   display: block;
   width: 100%;
   background: #e6e6e6;
   height: 50px;
   /* border-radius: 25px; */
   padding: 0 10px 0 18px;
   }
   .focus-input{
   display: block;
   position: absolute;
   border-radius: 25px;
   bottom: 0;
   left: 0;
   z-index: -1;
   width: 100%;
   height: 100%;
   box-shadow: 0px 0px 0px 0px;
   color: #c850c0;
   }
   .input:focus + .focus-input{
   animation:  anim-shadow 0.5s ease-in-out forwards;
   }
   @-webkit-keyframes anim-shadow{
   to {
   box-shadow:  0px 0px 70px 25px ;
   opacity: 0;
   }
   }
   @keyframes anim-shadow{
   to {
   box-shadow:  0px 0px 70px 25px ;
   opacity: 0;
   }
   }
   .symbol-input{
   font-size: 15px;
   display: flex;
   align-items: center;
   position: absolute;
   border-radius: 25px;
   bottom: 0;
   left: 0;
   width: 100%;
   height: 100%;
   padding-left: 35px;
   pointer-events: none;
   color: #666666;
   transition: all 0.4s
   }
   .input:focus + .focus-input + .symbol-input{
   color: #c850c0;
   padding-left: 28px;
   }
   .login-form-btn-container{
   width: 100%;
   display: flex;
   flex-wrap: wrap;
   justify-content: center;
   padding-top: 20px;
   }
   .login-form-btn{
   font-family:'poppins',sans-serif ;
   font-size: 15px;
   line-height: 1.5;
   color: #fff;
   background: linear-gradient(-135deg, #c850c0, #4158d0);;
   text-transform: uppercase;
   width: 100%;
   height: 50px;
   border-radius: 25px;
   display: flex;
   justify-content: center;
   align-items: center;
   padding: 0 25px ;
   transition: all 0.4s;
   border: none;
   }
   .login-form-btn:hover{
   background: #333333;
   }
   .text-center{
   text-align: center;
   }
   .txt1{
   font-family: 'poppins';
   font-size: 13px;
   line-height: 1.5;
   color: #666666;
   }
   .txt2{
   font-family: 'poppins';
   font-size: 13px;
   line-height: 1.5;
   color: #666666;
   }
   .p-t-1{
   padding-top: 12px;
   }
   .p-t-2{
   padding-top: 136px;
   }
   a{
   font-family: 'poppins', sans-serif;
   font-size: 14px;
   line-height: 1.7;
   color: #666666;
   margin: 0px;
   transition: all 0.4s;
   text-decoration: none;
   font-weight: 400;
   }
   a:focus{
   outline: none !important;
   }
   a:hover{
   color: #c850c0;
   }
   button{
   outline: none !important;
   border: none;
   background: transparent;
   }
   button:hover{
   cursor: pointer;
   }
   /* Responsive */
   @media (max-width: 992px){
   .wrap-login{
   padding: 177px 90px 33px 85px;
   }
   .login-pic{
   width: 35%;
   }
   .login-form{
   width: 50%;
   }
   }
   @media (max-width: 768px){
   .wrap-login{
   padding: 100px 80px 33px 80px;
   }
   .login-pic{
   display: none;
   }
   .login-form{
   width: 100%;
   } 
   }
   @media (max-width: 576px){
   .wrap-login{
   padding: 100px 15px 33px 15px;
   }
   }
</style>