(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '    <ul>                <li data-name="namespace:" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href=".html">Humps</a>                    </div>                    <div class="bd">                            <ul>                <li data-name="namespace:Humps_MailManager" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Humps/MailManager.html">MailManager</a>                    </div>                    <div class="bd">                            <ul>                <li data-name="namespace:Humps_MailManager_Contracts" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Humps/MailManager/Contracts.html">Contracts</a>                    </div>                    <div class="bd">                            <ul>                <li data-name="class:Humps_MailManager_Contracts_BodyPart" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Humps/MailManager/Contracts/BodyPart.html">BodyPart</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_Contracts_MailManager" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Humps/MailManager/Contracts/MailManager.html">MailManager</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_Contracts_Message" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Humps/MailManager/Contracts/Message.html">Message</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Humps_MailManager_EmailAddress" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/EmailAddress.html">EmailAddress</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_EmailDecoder" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/EmailDecoder.html">EmailDecoder</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_Folder" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/Folder.html">Folder</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_ImapBodyPart" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/ImapBodyPart.html">ImapBodyPart</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_ImapMailManager" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/ImapMailManager.html">ImapMailManager</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_ImapMessage" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/ImapMessage.html">ImapMessage</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_Mailbox" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/Mailbox.html">Mailbox</a>                    </div>                </li>                            <li data-name="class:Humps_MailManager_MailboxFactory" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Humps/MailManager/MailboxFactory.html">MailboxFactory</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    {"type": "Namespace", "link": "Humps.html", "name": "Humps", "doc": "Namespace Humps"},{"type": "Namespace", "link": "Humps/MailManager.html", "name": "Humps\\MailManager", "doc": "Namespace Humps\\MailManager"},{"type": "Namespace", "link": "Humps/MailManager/Contracts.html", "name": "Humps\\MailManager\\Contracts", "doc": "Namespace Humps\\MailManager\\Contracts"},
            {"type": "Interface", "fromName": "Humps\\MailManager\\Contracts", "fromLink": "Humps/MailManager/Contracts.html", "link": "Humps/MailManager/Contracts/BodyPart.html", "name": "Humps\\MailManager\\Contracts\\BodyPart", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getBodyType", "name": "Humps\\MailManager\\Contracts\\BodyPart::getBodyType", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setBodyType", "name": "Humps\\MailManager\\Contracts\\BodyPart::setBodyType", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getEncoding", "name": "Humps\\MailManager\\Contracts\\BodyPart::getEncoding", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setEncoding", "name": "Humps\\MailManager\\Contracts\\BodyPart::setEncoding", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getSubtype", "name": "Humps\\MailManager\\Contracts\\BodyPart::getSubtype", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setSubtype", "name": "Humps\\MailManager\\Contracts\\BodyPart::setSubtype", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getSection", "name": "Humps\\MailManager\\Contracts\\BodyPart::getSection", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setSection", "name": "Humps\\MailManager\\Contracts\\BodyPart::setSection", "doc": "&quot;\n&quot;"},
            
            {"type": "Interface", "fromName": "Humps\\MailManager\\Contracts", "fromLink": "Humps/MailManager/Contracts.html", "link": "Humps/MailManager/Contracts/MailManager.html", "name": "Humps\\MailManager\\Contracts\\MailManager", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getConnection", "name": "Humps\\MailManager\\Contracts\\MailManager::getConnection", "doc": "&quot;Returns the current connection&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getAllFolders", "name": "Humps\\MailManager\\Contracts\\MailManager::getAllFolders", "doc": "&quot;Returns all the folder names for the given mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_openFolder", "name": "Humps\\MailManager\\Contracts\\MailManager::openFolder", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessagesBySender", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessagesBySender", "doc": "&quot;Gets all messages for the given sender&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_searchMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::searchMessages", "doc": "&quot;Search for a message by the given criteria&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessage", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessage", "doc": "&quot;Get Message by message Number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessageByUid", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessageByUid", "doc": "&quot;Get message by uid&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getAllMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::getAllMessages", "doc": "&quot;Returns all message details for the given mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMailboxName", "name": "Humps\\MailManager\\Contracts\\MailManager::getMailboxName", "doc": "&quot;Returns the name of the mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessageCount", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessageCount", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_deleteMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::deleteMessages", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_deleteAllMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::deleteAllMessages", "doc": "&quot;Deletes all messages from the given folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_emptyTrash", "name": "Humps\\MailManager\\Contracts\\MailManager::emptyTrash", "doc": "&quot;Deletes the messages from the trash folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_moveToTrash", "name": "Humps\\MailManager\\Contracts\\MailManager::moveToTrash", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_refresh", "name": "Humps\\MailManager\\Contracts\\MailManager::refresh", "doc": "&quot;Resets the connection to the mailserver&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_closeConnection", "name": "Humps\\MailManager\\Contracts\\MailManager::closeConnection", "doc": "&quot;Closes the connection to the mail server&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessageNumbers", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessageNumbers", "doc": "&quot;Returns the message numbers for the given messages&quot;"},
            
            {"type": "Interface", "fromName": "Humps\\MailManager\\Contracts", "fromLink": "Humps/MailManager/Contracts.html", "link": "Humps/MailManager/Contracts/Message.html", "name": "Humps\\MailManager\\Contracts\\Message", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getMessageNo", "name": "Humps\\MailManager\\Contracts\\Message::getMessageNo", "doc": "&quot;Returns the message number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getSubject", "name": "Humps\\MailManager\\Contracts\\Message::getSubject", "doc": "&quot;Returns the subject of the E-mail&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getFrom", "name": "Humps\\MailManager\\Contracts\\Message::getFrom", "doc": "&quot;Return the from addresses or an array of from addresses if $asString is false&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getCC", "name": "Humps\\MailManager\\Contracts\\Message::getCC", "doc": "&quot;Return an array of CC addresses&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getTo", "name": "Humps\\MailManager\\Contracts\\Message::getTo", "doc": "&quot;Returns an array of to addresses&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_setTextBody", "name": "Humps\\MailManager\\Contracts\\Message::setTextBody", "doc": "&quot;Sets the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getTextBody", "name": "Humps\\MailManager\\Contracts\\Message::getTextBody", "doc": "&quot;Returns the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getHtmlBody", "name": "Humps\\MailManager\\Contracts\\Message::getHtmlBody", "doc": "&quot;Returns the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_setHtmlBody", "name": "Humps\\MailManager\\Contracts\\Message::setHtmlBody", "doc": "&quot;Sets the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getSize", "name": "Humps\\MailManager\\Contracts\\Message::getSize", "doc": "&quot;returns the size of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getDate", "name": "Humps\\MailManager\\Contracts\\Message::getDate", "doc": "&quot;A formatted version of the date&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getRawDate", "name": "Humps\\MailManager\\Contracts\\Message::getRawDate", "doc": "&quot;The raw date as returned from the server&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getHeaderDate", "name": "Humps\\MailManager\\Contracts\\Message::getHeaderDate", "doc": "&quot;Any header date as returned from the server, usually same as getRawDate().&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_setAttachments", "name": "Humps\\MailManager\\Contracts\\Message::setAttachments", "doc": "&quot;Sets the attachments&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getAttachments", "name": "Humps\\MailManager\\Contracts\\Message::getAttachments", "doc": "&quot;Returns an array of attachments&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getMessage", "name": "Humps\\MailManager\\Contracts\\Message::getMessage", "doc": "&quot;Get the entire message&quot;"},
            
            
            {"type": "Class", "fromName": "Humps\\MailManager\\Contracts", "fromLink": "Humps/MailManager/Contracts.html", "link": "Humps/MailManager/Contracts/BodyPart.html", "name": "Humps\\MailManager\\Contracts\\BodyPart", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getBodyType", "name": "Humps\\MailManager\\Contracts\\BodyPart::getBodyType", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setBodyType", "name": "Humps\\MailManager\\Contracts\\BodyPart::setBodyType", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getEncoding", "name": "Humps\\MailManager\\Contracts\\BodyPart::getEncoding", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setEncoding", "name": "Humps\\MailManager\\Contracts\\BodyPart::setEncoding", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getSubtype", "name": "Humps\\MailManager\\Contracts\\BodyPart::getSubtype", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setSubtype", "name": "Humps\\MailManager\\Contracts\\BodyPart::setSubtype", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_getSection", "name": "Humps\\MailManager\\Contracts\\BodyPart::getSection", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\BodyPart", "fromLink": "Humps/MailManager/Contracts/BodyPart.html", "link": "Humps/MailManager/Contracts/BodyPart.html#method_setSection", "name": "Humps\\MailManager\\Contracts\\BodyPart::setSection", "doc": "&quot;\n&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager\\Contracts", "fromLink": "Humps/MailManager/Contracts.html", "link": "Humps/MailManager/Contracts/MailManager.html", "name": "Humps\\MailManager\\Contracts\\MailManager", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getConnection", "name": "Humps\\MailManager\\Contracts\\MailManager::getConnection", "doc": "&quot;Returns the current connection&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getAllFolders", "name": "Humps\\MailManager\\Contracts\\MailManager::getAllFolders", "doc": "&quot;Returns all the folder names for the given mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_openFolder", "name": "Humps\\MailManager\\Contracts\\MailManager::openFolder", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessagesBySender", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessagesBySender", "doc": "&quot;Gets all messages for the given sender&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_searchMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::searchMessages", "doc": "&quot;Search for a message by the given criteria&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessage", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessage", "doc": "&quot;Get Message by message Number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessageByUid", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessageByUid", "doc": "&quot;Get message by uid&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getAllMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::getAllMessages", "doc": "&quot;Returns all message details for the given mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMailboxName", "name": "Humps\\MailManager\\Contracts\\MailManager::getMailboxName", "doc": "&quot;Returns the name of the mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessageCount", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessageCount", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_deleteMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::deleteMessages", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_deleteAllMessages", "name": "Humps\\MailManager\\Contracts\\MailManager::deleteAllMessages", "doc": "&quot;Deletes all messages from the given folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_emptyTrash", "name": "Humps\\MailManager\\Contracts\\MailManager::emptyTrash", "doc": "&quot;Deletes the messages from the trash folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_moveToTrash", "name": "Humps\\MailManager\\Contracts\\MailManager::moveToTrash", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_refresh", "name": "Humps\\MailManager\\Contracts\\MailManager::refresh", "doc": "&quot;Resets the connection to the mailserver&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_closeConnection", "name": "Humps\\MailManager\\Contracts\\MailManager::closeConnection", "doc": "&quot;Closes the connection to the mail server&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\MailManager", "fromLink": "Humps/MailManager/Contracts/MailManager.html", "link": "Humps/MailManager/Contracts/MailManager.html#method_getMessageNumbers", "name": "Humps\\MailManager\\Contracts\\MailManager::getMessageNumbers", "doc": "&quot;Returns the message numbers for the given messages&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager\\Contracts", "fromLink": "Humps/MailManager/Contracts.html", "link": "Humps/MailManager/Contracts/Message.html", "name": "Humps\\MailManager\\Contracts\\Message", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getMessageNo", "name": "Humps\\MailManager\\Contracts\\Message::getMessageNo", "doc": "&quot;Returns the message number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getSubject", "name": "Humps\\MailManager\\Contracts\\Message::getSubject", "doc": "&quot;Returns the subject of the E-mail&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getFrom", "name": "Humps\\MailManager\\Contracts\\Message::getFrom", "doc": "&quot;Return the from addresses or an array of from addresses if $asString is false&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getCC", "name": "Humps\\MailManager\\Contracts\\Message::getCC", "doc": "&quot;Return an array of CC addresses&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getTo", "name": "Humps\\MailManager\\Contracts\\Message::getTo", "doc": "&quot;Returns an array of to addresses&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_setTextBody", "name": "Humps\\MailManager\\Contracts\\Message::setTextBody", "doc": "&quot;Sets the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getTextBody", "name": "Humps\\MailManager\\Contracts\\Message::getTextBody", "doc": "&quot;Returns the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getHtmlBody", "name": "Humps\\MailManager\\Contracts\\Message::getHtmlBody", "doc": "&quot;Returns the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_setHtmlBody", "name": "Humps\\MailManager\\Contracts\\Message::setHtmlBody", "doc": "&quot;Sets the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getSize", "name": "Humps\\MailManager\\Contracts\\Message::getSize", "doc": "&quot;returns the size of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getDate", "name": "Humps\\MailManager\\Contracts\\Message::getDate", "doc": "&quot;A formatted version of the date&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getRawDate", "name": "Humps\\MailManager\\Contracts\\Message::getRawDate", "doc": "&quot;The raw date as returned from the server&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getHeaderDate", "name": "Humps\\MailManager\\Contracts\\Message::getHeaderDate", "doc": "&quot;Any header date as returned from the server, usually same as getRawDate().&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_setAttachments", "name": "Humps\\MailManager\\Contracts\\Message::setAttachments", "doc": "&quot;Sets the attachments&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getAttachments", "name": "Humps\\MailManager\\Contracts\\Message::getAttachments", "doc": "&quot;Returns an array of attachments&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Contracts\\Message", "fromLink": "Humps/MailManager/Contracts/Message.html", "link": "Humps/MailManager/Contracts/Message.html#method_getMessage", "name": "Humps\\MailManager\\Contracts\\Message::getMessage", "doc": "&quot;Get the entire message&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/EmailAddress.html", "name": "Humps\\MailManager\\EmailAddress", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method___construct", "name": "Humps\\MailManager\\EmailAddress::__construct", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method_getMailbox", "name": "Humps\\MailManager\\EmailAddress::getMailbox", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method_setMailbox", "name": "Humps\\MailManager\\EmailAddress::setMailbox", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method_getHost", "name": "Humps\\MailManager\\EmailAddress::getHost", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method_getEmailAddress", "name": "Humps\\MailManager\\EmailAddress::getEmailAddress", "doc": "&quot;Returns the Email address as a string by calling __toString()&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method_setHost", "name": "Humps\\MailManager\\EmailAddress::setHost", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailAddress", "fromLink": "Humps/MailManager/EmailAddress.html", "link": "Humps/MailManager/EmailAddress.html#method___toString", "name": "Humps\\MailManager\\EmailAddress::__toString", "doc": "&quot;How to convert object to string&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/EmailDecoder.html", "name": "Humps\\MailManager\\EmailDecoder", "doc": "&quot;Attempts to decode Email messages with unknown decoding\nClass Decoder&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\EmailDecoder", "fromLink": "Humps/MailManager/EmailDecoder.html", "link": "Humps/MailManager/EmailDecoder.html#method_decode", "name": "Humps\\MailManager\\EmailDecoder::decode", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailDecoder", "fromLink": "Humps/MailManager/EmailDecoder.html", "link": "Humps/MailManager/EmailDecoder.html#method_decodeQP", "name": "Humps\\MailManager\\EmailDecoder::decodeQP", "doc": "&quot;A quoted printable decode without throwing the errors that PHP&#039;s native function can throw.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\EmailDecoder", "fromLink": "Humps/MailManager/EmailDecoder.html", "link": "Humps/MailManager/EmailDecoder.html#method_guessEncoding", "name": "Humps\\MailManager\\EmailDecoder::guessEncoding", "doc": "&quot;\n&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/Folder.html", "name": "Humps\\MailManager\\Folder", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Folder", "fromLink": "Humps/MailManager/Folder.html", "link": "Humps/MailManager/Folder.html#method___construct", "name": "Humps\\MailManager\\Folder::__construct", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Folder", "fromLink": "Humps/MailManager/Folder.html", "link": "Humps/MailManager/Folder.html#method_getMailboxName", "name": "Humps\\MailManager\\Folder::getMailboxName", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Folder", "fromLink": "Humps/MailManager/Folder.html", "link": "Humps/MailManager/Folder.html#method_getName", "name": "Humps\\MailManager\\Folder::getName", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Folder", "fromLink": "Humps/MailManager/Folder.html", "link": "Humps/MailManager/Folder.html#method_getAttributes", "name": "Humps\\MailManager\\Folder::getAttributes", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Folder", "fromLink": "Humps/MailManager/Folder.html", "link": "Humps/MailManager/Folder.html#method_getDelimiter", "name": "Humps\\MailManager\\Folder::getDelimiter", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Folder", "fromLink": "Humps/MailManager/Folder.html", "link": "Humps/MailManager/Folder.html#method_getDetails", "name": "Humps\\MailManager\\Folder::getDetails", "doc": "&quot;\n&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/ImapBodyPart.html", "name": "Humps\\MailManager\\ImapBodyPart", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getName", "name": "Humps\\MailManager\\ImapBodyPart::getName", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setName", "name": "Humps\\MailManager\\ImapBodyPart::setName", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getId", "name": "Humps\\MailManager\\ImapBodyPart::getId", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setId", "name": "Humps\\MailManager\\ImapBodyPart::setId", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_isEmbedded", "name": "Humps\\MailManager\\ImapBodyPart::isEmbedded", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setEmbedded", "name": "Humps\\MailManager\\ImapBodyPart::setEmbedded", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getCharset", "name": "Humps\\MailManager\\ImapBodyPart::getCharset", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setCharset", "name": "Humps\\MailManager\\ImapBodyPart::setCharset", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getBodyType", "name": "Humps\\MailManager\\ImapBodyPart::getBodyType", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setBodyType", "name": "Humps\\MailManager\\ImapBodyPart::setBodyType", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getEncoding", "name": "Humps\\MailManager\\ImapBodyPart::getEncoding", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setEncoding", "name": "Humps\\MailManager\\ImapBodyPart::setEncoding", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getSubtype", "name": "Humps\\MailManager\\ImapBodyPart::getSubtype", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setSubtype", "name": "Humps\\MailManager\\ImapBodyPart::setSubtype", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_getSection", "name": "Humps\\MailManager\\ImapBodyPart::getSection", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapBodyPart", "fromLink": "Humps/MailManager/ImapBodyPart.html", "link": "Humps/MailManager/ImapBodyPart.html#method_setSection", "name": "Humps\\MailManager\\ImapBodyPart::setSection", "doc": "&quot;\n&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/ImapMailManager.html", "name": "Humps\\MailManager\\ImapMailManager", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method___construct", "name": "Humps\\MailManager\\ImapMailManager::__construct", "doc": "&quot;MailManager constructor.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_setOutputEncoding", "name": "Humps\\MailManager\\ImapMailManager::setOutputEncoding", "doc": "&quot;Sets the output encoding to the given encoding. By default the output encoding is\nUTF-8, so this is only required when you want your output to use a different encoding.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessageList", "name": "Humps\\MailManager\\ImapMailManager::getMessageList", "doc": "&quot;Returns a comma delimited message list from the given array.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessageNumbers", "name": "Humps\\MailManager\\ImapMailManager::getMessageNumbers", "doc": "&quot;Returns an array of message numbers for the given messages.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_flagAsRead", "name": "Humps\\MailManager\\ImapMailManager::flagAsRead", "doc": "&quot;Flags the given messages as read&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_setFlag", "name": "Humps\\MailManager\\ImapMailManager::setFlag", "doc": "&quot;Sets the given flag on the given messages&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getUnreadMessages", "name": "Humps\\MailManager\\ImapMailManager::getUnreadMessages", "doc": "&quot;Returns all unread messages&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_searchMessages", "name": "Humps\\MailManager\\ImapMailManager::searchMessages", "doc": "&quot;Search for a message by the given criteria&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessage", "name": "Humps\\MailManager\\ImapMailManager::getMessage", "doc": "&quot;Gets the message by the given message number.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_fetchStructure", "name": "Humps\\MailManager\\ImapMailManager::fetchStructure", "doc": "&quot;Fetches the structure of the E-mail (see: &lt;a href=\&quot; http:\/\/php.net\/manual\/en\/function.imap-fetchbody.php\&quot;&gt;http:\/\/php.net\/manual\/en\/function.imap-fetchbody.php&lt;\/a&gt;)&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_fetchBodyParts", "name": "Humps\\MailManager\\ImapMailManager::fetchBodyParts", "doc": "&quot;Returns an array of BodyParts . The array is broken down into sections, so all parts from section 1\nwill be at index 0 (&lt;code&gt;$bodyParts[0]&lt;\/code&gt;), part 2 at index 1 (&lt;code&gt;$bodyParts[0]&lt;\/code&gt;) etc.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_fetchBody", "name": "Humps\\MailManager\\ImapMailManager::fetchBody", "doc": "&quot;Fetches the given body part (see: &lt;a href=\&quot; http:\/\/php.net\/manual\/en\/function.imap-fetchbody.php\&quot;&gt;http:\/\/php.net\/manual\/en\/function.imap-fetchbody.php&lt;\/a&gt;)&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getAttachments", "name": "Humps\\MailManager\\ImapMailManager::getAttachments", "doc": "&quot;TODO This needs to be changed so an array of Attachment objects are returned\nGet the E-mail attachment details for the given message number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getEmbeddedImages", "name": "Humps\\MailManager\\ImapMailManager::getEmbeddedImages", "doc": "&quot;TODO implement the replace function so it works for non cid messages\nGets the embedded images for the given messages and alters the body accordingly\nImportant: This function downloads images to the given path and places them inside an \/embedded\/{messageNo} folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_downloadAttachments", "name": "Humps\\MailManager\\ImapMailManager::downloadAttachments", "doc": "&quot;Download the attachments for the given message number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getReadMessages", "name": "Humps\\MailManager\\ImapMailManager::getReadMessages", "doc": "&quot;Returns all messages marked as read\/seen&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_flagAsImportant", "name": "Humps\\MailManager\\ImapMailManager::flagAsImportant", "doc": "&quot;Flags the given messages as important&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getImportantMessages", "name": "Humps\\MailManager\\ImapMailManager::getImportantMessages", "doc": "&quot;Returns all messages flagged as important (i.e. FLAGGED)&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_flagAsAnswered", "name": "Humps\\MailManager\\ImapMailManager::flagAsAnswered", "doc": "&quot;Flags the given messages as answered&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getAnsweredMessages", "name": "Humps\\MailManager\\ImapMailManager::getAnsweredMessages", "doc": "&quot;Returns all messages flagged as important (i.e. FLAGGED)&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getUnansweredMessages", "name": "Humps\\MailManager\\ImapMailManager::getUnansweredMessages", "doc": "&quot;Returns all messages flagged as unanswered (i.e. FLAGGED)&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getAllFolders", "name": "Humps\\MailManager\\ImapMailManager::getAllFolders", "doc": "&quot;Returns all the folders for the given mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesBySender", "name": "Humps\\MailManager\\ImapMailManager::getMessagesBySender", "doc": "&quot;Gets all messages for the given sender&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesBySubject", "name": "Humps\\MailManager\\ImapMailManager::getMessagesBySubject", "doc": "&quot;Gets messages by subject&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesByCC", "name": "Humps\\MailManager\\ImapMailManager::getMessagesByCC", "doc": "&quot;Gets the messages by CC&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesByBCC", "name": "Humps\\MailManager\\ImapMailManager::getMessagesByBCC", "doc": "&quot;Gets the messages by BCC&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesByReceiver", "name": "Humps\\MailManager\\ImapMailManager::getMessagesByReceiver", "doc": "&quot;Gets the messages by the to field&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesByDate", "name": "Humps\\MailManager\\ImapMailManager::getMessagesByDate", "doc": "&quot;Gets the messages sent on the specified date&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesBefore", "name": "Humps\\MailManager\\ImapMailManager::getMessagesBefore", "doc": "&quot;Gets the messages sent before the specified date&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesBetween", "name": "Humps\\MailManager\\ImapMailManager::getMessagesBetween", "doc": "&quot;Gets the messages sent between the specified dates&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessagesAfter", "name": "Humps\\MailManager\\ImapMailManager::getMessagesAfter", "doc": "&quot;Gets the messages sent after the specified date&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessageByUid", "name": "Humps\\MailManager\\ImapMailManager::getMessageByUid", "doc": "&quot;Get the message by the unique identifier&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getAllMessages", "name": "Humps\\MailManager\\ImapMailManager::getAllMessages", "doc": "&quot;Returns all message details for the given mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMessageCount", "name": "Humps\\MailManager\\ImapMailManager::getMessageCount", "doc": "&quot;Returns the number of messages in the mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMailboxName", "name": "Humps\\MailManager\\ImapMailManager::getMailboxName", "doc": "&quot;Get the full mailbox name&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getFolderName", "name": "Humps\\MailManager\\ImapMailManager::getFolderName", "doc": "&quot;Gets the current folder name&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getUnreadMessageCount", "name": "Humps\\MailManager\\ImapMailManager::getUnreadMessageCount", "doc": "&quot;Returns the number of unread messages&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getConnection", "name": "Humps\\MailManager\\ImapMailManager::getConnection", "doc": "&quot;Returns the current imap connection, which can be passed in to php&#039;s native imap functions.&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_getMailbox", "name": "Humps\\MailManager\\ImapMailManager::getMailbox", "doc": "&quot;Returns the Mailbox object&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_emptyTrash", "name": "Humps\\MailManager\\ImapMailManager::emptyTrash", "doc": "&quot;Deletes all messages from the trash folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_deleteAllMessages", "name": "Humps\\MailManager\\ImapMailManager::deleteAllMessages", "doc": "&quot;Deletes all messages from the given folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_openFolder", "name": "Humps\\MailManager\\ImapMailManager::openFolder", "doc": "&quot;Opens a connection to the given folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_refresh", "name": "Humps\\MailManager\\ImapMailManager::refresh", "doc": "&quot;Resets\/Refreshes the connection to the mail server&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_deleteMessages", "name": "Humps\\MailManager\\ImapMailManager::deleteMessages", "doc": "&quot;Deletes the given message from the mailbox&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_moveToTrash", "name": "Humps\\MailManager\\ImapMailManager::moveToTrash", "doc": "&quot;Moves the given messages to the trash folder&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMailManager", "fromLink": "Humps/MailManager/ImapMailManager.html", "link": "Humps/MailManager/ImapMailManager.html#method_closeConnection", "name": "Humps\\MailManager\\ImapMailManager::closeConnection", "doc": "&quot;Closes the connection to the mail server&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/ImapMessage.html", "name": "Humps\\MailManager\\ImapMessage", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method___construct", "name": "Humps\\MailManager\\ImapMessage::__construct", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getMessageNo", "name": "Humps\\MailManager\\ImapMessage::getMessageNo", "doc": "&quot;Returns the message number&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getSubject", "name": "Humps\\MailManager\\ImapMessage::getSubject", "doc": "&quot;Returns the subject of the E-mail&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getFrom", "name": "Humps\\MailManager\\ImapMessage::getFrom", "doc": "&quot;Return the from addresses or an array of from addresses if $asString is false&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getCC", "name": "Humps\\MailManager\\ImapMessage::getCC", "doc": "&quot;Return an array of CC addresses&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getTo", "name": "Humps\\MailManager\\ImapMessage::getTo", "doc": "&quot;Returns an array of to addresses&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_setHtmlBody", "name": "Humps\\MailManager\\ImapMessage::setHtmlBody", "doc": "&quot;Sets the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getTextBody", "name": "Humps\\MailManager\\ImapMessage::getTextBody", "doc": "&quot;Returns the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getHtmlBody", "name": "Humps\\MailManager\\ImapMessage::getHtmlBody", "doc": "&quot;Returns the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_setTextBody", "name": "Humps\\MailManager\\ImapMessage::setTextBody", "doc": "&quot;Sets the body of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_setInlineAttachments", "name": "Humps\\MailManager\\ImapMessage::setInlineAttachments", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getSize", "name": "Humps\\MailManager\\ImapMessage::getSize", "doc": "&quot;returns the size of the message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getDate", "name": "Humps\\MailManager\\ImapMessage::getDate", "doc": "&quot;A formatted version of the date&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getRawDate", "name": "Humps\\MailManager\\ImapMessage::getRawDate", "doc": "&quot;The raw date as returned from the server&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getHeaderDate", "name": "Humps\\MailManager\\ImapMessage::getHeaderDate", "doc": "&quot;Any header date as returned from the server, usually same as getRawDate().&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_setAttachments", "name": "Humps\\MailManager\\ImapMessage::setAttachments", "doc": "&quot;Sets the attachments&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getAttachments", "name": "Humps\\MailManager\\ImapMessage::getAttachments", "doc": "&quot;Returns an array of attachments&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_getMessage", "name": "Humps\\MailManager\\ImapMessage::getMessage", "doc": "&quot;Get the entire message&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method_isUnread", "name": "Humps\\MailManager\\ImapMessage::isUnread", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\ImapMessage", "fromLink": "Humps/MailManager/ImapMessage.html", "link": "Humps/MailManager/ImapMessage.html#method___toString", "name": "Humps\\MailManager\\ImapMessage::__toString", "doc": "&quot;\n&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/Mailbox.html", "name": "Humps\\MailManager\\Mailbox", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method___construct", "name": "Humps\\MailManager\\Mailbox::__construct", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_isSsl", "name": "Humps\\MailManager\\Mailbox::isSsl", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setSsl", "name": "Humps\\MailManager\\Mailbox::setSsl", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_isValidateCert", "name": "Humps\\MailManager\\Mailbox::isValidateCert", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setValidateCert", "name": "Humps\\MailManager\\Mailbox::setValidateCert", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_getServer", "name": "Humps\\MailManager\\Mailbox::getServer", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setServer", "name": "Humps\\MailManager\\Mailbox::setServer", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_getUsername", "name": "Humps\\MailManager\\Mailbox::getUsername", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setUsername", "name": "Humps\\MailManager\\Mailbox::setUsername", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_getPassword", "name": "Humps\\MailManager\\Mailbox::getPassword", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setPassword", "name": "Humps\\MailManager\\Mailbox::setPassword", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_getPort", "name": "Humps\\MailManager\\Mailbox::getPort", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setPort", "name": "Humps\\MailManager\\Mailbox::setPort", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_getFolder", "name": "Humps\\MailManager\\Mailbox::getFolder", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "Humps\\MailManager\\Mailbox", "fromLink": "Humps/MailManager/Mailbox.html", "link": "Humps/MailManager/Mailbox.html#method_setFolder", "name": "Humps\\MailManager\\Mailbox::setFolder", "doc": "&quot;\n&quot;"},
            
            {"type": "Class", "fromName": "Humps\\MailManager", "fromLink": "Humps/MailManager.html", "link": "Humps/MailManager/MailboxFactory.html", "name": "Humps\\MailManager\\MailboxFactory", "doc": "&quot;\n&quot;"},
                                                        {"type": "Method", "fromName": "Humps\\MailManager\\MailboxFactory", "fromLink": "Humps/MailManager/MailboxFactory.html", "link": "Humps/MailManager/MailboxFactory.html#method_create", "name": "Humps\\MailManager\\MailboxFactory::create", "doc": "&quot;\n&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


