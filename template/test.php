<div class="about-page mt-w">
    <div class="container custom-container">
        <a href="#" id="LoginWithAmazon">
            <img border="0" alt="Login with Amazon"
                src="https://images-na.ssl-images-amazon.com/images/G/01/lwa/btnLWA_gold_156x32.png"
                width="156" height="32" />
        </a>
    </div>
</div>

<script type="text/javascript">
  document.getElementById('LoginWithAmazon').onclick = function() {
    setTimeout(window.doLogin, 1);
    return false;
  };

  window.doLogin = function() {
        options = {};
        options.scope = 'profile';
        options.pkce = true;
        amazon.Login.authorize(options, function(response) {
            if ( response.error ) {
                alert('oauth error ' + response.error);
            return;
            }
            amazon.Login.retrieveToken(response.code, function(response) {
                if ( response.error ) {
                    alert('oauth error ' + response.error);
                return;
                }
                amazon.Login.retrieveProfile(response.access_token, function(response) {
                    alert(response.profile.Name + ' ' + response.  + ' ' + response.profile.PrimaryEmail + ' ' + response.profile.CustomerId);
                });
            });
        });
   };
</script>