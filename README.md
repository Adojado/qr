<h1>Magento 2 QR code</h1>
<ol>
    <h2><li>Installation</li></h2>
    <ul>
        <h3><li>Put files into server</li></h3> 
        Put files from repository to 
        <code>MAGENTO_ROOT/app/code/Adojado/Qr</code>
        path.
        <h3><li>Install via composer</li></h3>
        Run on your server cmmand 
        <code>composer require adojado/qr</code>
    </ul>
After chose your installation method run magento comile code on your server 
<br />
<code>
    php bin/magento setup:upgrade <br />
    php bin/magento setup:static-content:deploy
</code>
<h2><li>Configuration</li></h2>
Configuration of module is in the MagentoAdminPanel->stores->configuration->Catalog->catalog->Qr
<h2><li>Using</li></h2>
After complete configuration run command <br>
<code>php bin/magento qr:add-to-queue</code>
<br>
This will add your product to rabbit queue after rabbit send product t magento to process attribute <b>qr</b> will by updated and qr base64 text will by saved in database, you can see qr code on product page
</ol>
