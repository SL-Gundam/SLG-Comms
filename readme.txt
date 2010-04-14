Just run install.php. It will guide you through the process. To update your script to a new version install.php also needs to be run.

Because the ventrilo status program is copyright protected i can't pack it into this release. Please download a version of the Ventrilo server from www.ventrilo.com and place the "ventrilo_status.exe" (Win32 executable) or "ventrilo_status" (Unix / Linux executable) in a folder accessible by SLG Comms. make sure that the files have full rights especially under Unix / Linux. It requires read, write and execute (777 for everyone) access. There are ventrilo_status excutable's available on www.ventrilo.com but these havn't been tested but theoretically they should work. It's required that the Ventrilo status program is located within the SLG Comms root dir or in a sub directory of the SLG Comms root dir. The name of this sub directory doesn't matter

At the moment i can't think of anything that should go mayorly wrong and you wouldn't get a good error. If you do encounter problems which you don't understand please go to http://slg.sourceforge.net. Click on "project" and then on Bugs. Please fill in your problem there.

on a side note: The difference with the 2 templates delivered with this package is very small. The only difference is how the admin menu is setup. More of an example if anyone ever wants to create his or her own templates for SLG Comms and you want a different menu design.

I'd like to thank the men and women who made Emule (www.emule-project.net) because the web interface they created gave me good ideas for some enhancements to the script.

I'd like to thank the men and women who made the PhpBB forum bcause it gave me a lot of good ideas.

Since SLG Comms v2.2.0 the way Ventrilo server data is saved has changed a bit which makes earlier cached server data invalid. This is not a problem. You only have to wait for the server data to be updated for the first time. It depends on your caching settings when this happens on a per server basis.

I noticed that step 3 in the installation is kinda useless when you're just updating SLG Comms from an earlier version. This has been fixed in v2.2.0

In earlier versions (Earlier then v2.2.0) there was a bug in the updating procedure from earlier versions when using a database with SLG Comms. I'm sorry i didn't find this bug earlier. It couldn't have caused any problems besides you worrying about whats gone wrong because in all earlier versions the updating scripts only purpose and goal was to update the version number.

It seems SLG Comms drastically loses performance when there are a lot of channels and clients (400 or more channels). This is because the template has grown to a very large size at that point. This causes the replacement of language placeholders by text to be very time consuming. If you have a good idea about how to fix this issue, let me know by filling in a bug on the projects homepage ( http://sourceforge.net/tracker/?func=add&group_id=142081&atid=751417 ). Since PHP 5 performance seems to have improved greatly on this part (about 2-3 times faster).

If you want to use the "XOOPS + CBB" forum profile you need to place this script in a sub directory of XOOPS. It doesn't matter what directory it is so you can even create a new directory for SLG Comms.

NEVER activate debug mode in config.inc.php unless you absolutely need to do that because it could expose usernames, passwords and other vulnerable information.
