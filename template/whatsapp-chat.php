<style>
#whatsapp_chat_widget{
    position:fixed;
    bottom:20px;
    right:20px;
    width:60px;
    height:60px;
    border-radius:100%;
    -moz-border-radius:100%;
    -webkit-border-radius:100%;
    background-color:#25D366;
    z-index:10000;
    text-align:center;
}

#whatsapp_chat_widget img{
    width:35px;
    margin:auto;
    position:relative;
    top:13px;
}

@media screen and (max-width:1023px){
    #whatsapp_chat_widget{
        bottom:70px;
        width:40px;
        height:40px;
        display:none
    }

    #whatsapp_chat_widget img{
        width:23px;
        margin:auto;
        position:relative;
        top:8px;
    }
}

</style>
<div id="whatsapp_chat_widget"> 
        <div class="wa-chat-box-send">
            <a role="button" target="_blank" href="https://api.whatsapp.com/send?phone=919910200043&amp;text=Hello, I have a question about" title="WhatsApp" class="wa-chat-box-content-send-btn">
                <img src="<?=DOMAIN?>Content/assets/images/common/WhatsApp-Logo.png" />
            </a>
        </div>
    </div>
</div>