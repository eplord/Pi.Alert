<?php
//------------------------------------------------------------------------------
//  Pi.Alert
//  Open Source Network Guard / WIFI & LAN intrusion detector 
//
//  devices.php - Front module. Server side. Manage Devices
//------------------------------------------------------------------------------
//  Puche 2021        pi.alert.application@gmail.com        GNU GPLv3
//  jokob-sk 2022        jokob.sk@gmail.com        GNU GPLv3
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
?>

<?php
  require 'php/templates/header.php';
?>
<!-- Page ------------------------------------------------------------------ -->
<div class="content-wrapper">

<!-- Content header--------------------------------------------------------- -->
    <section class="content-header">
    <?php require 'php/templates/notification.php'; ?>
      <h1 id="pageTitle">
         Maintenance tools
      </h1>
    </section>

    <!-- Main content ---------------------------------------------------------- -->
    <section class="content">


  <?php

$pia_db = str_replace('front', 'db', getcwd()).'/pialert.db';
//echo $pia_db;
$pia_db_size = number_format(filesize($pia_db),0,",",".") . ' Byte';
//echo $pia_db_size;
$pia_db_mod = date ("F d Y H:i:s", filemtime($pia_db));


if (!file_exists('../db/setting_stoparpscan')) {
  $execstring = 'ps -f -u root | grep "sudo arp-scan" 2>&1';
  $pia_arpscans = "";
  exec($execstring, $pia_arpscans);
  $pia_arpscans_result = sizeof($pia_arpscans).' scan(s) currently running';
} else {
  $pia_arpscans_result = '<span style="color:red;">arp-scan is currently disabled</span>';
}

$Pia_Archive_Path = str_replace('front', 'db', getcwd()).'/';
$Pia_Archive_count = 0;
$files = glob($Pia_Archive_Path."*.zip");
if ($files){
 $Pia_Archive_count = count($files);
}

$latestfiles = glob($Pia_Archive_Path."*.zip");
natsort($latestfiles);
$latestfiles = array_reverse($latestfiles,False);
$latestbackup = $latestfiles[0];
$latestbackup_date = date ("Y-m-d H:i:s", filemtime($latestbackup));

if (submit) {
  $pia_skin_set_dir = '../db/';
  $pia_skin_selector = htmlspecialchars($_POST['skinselector']);
  $pia_installed_skins = array('skin-black-light', 'skin-black', 'skin-blue-light', 'skin-blue', 'skin-green-light', 'skin-green', 'skin-purple-light', 'skin-purple', 'skin-red-light', 'skin-red', 'skin-yellow-light', 'skin-yellow',);
  if (in_array($pia_skin_selector, $pia_installed_skins)) {
    foreach ($pia_installed_skins as $file) {
      unlink ($pia_skin_set_dir.'/'.$file);
    }

    foreach ($pia_installed_skins as $file) {
      if (file_exists($pia_skin_set_dir.'/'.$file)) {
          $pia_skin_error = True;
          break;
      } else {
          $pia_skin_error = False;
      }
    }

    if ($pia_skin_error == False) {
      $testskin = fopen($pia_skin_set_dir.$pia_skin_selector, 'w');
      $pia_skin_test = '';
      echo("<meta http-equiv='refresh' content='1'>"); 
    } else {
      $pia_skin_test = '';
      echo("<meta http-equiv='refresh' content='1'>");
    }    
  }
}
  ?>

<div class="db_info_table">
    <div class="db_info_table_row">
        <div class="db_info_table_cell">Database-Path</div>
        <div class="db_info_table_cell">
            <?php echo $pia_db;?>
        </div>
    </div>
    <div class="db_info_table_row">
        <div class="db_info_table_cell">Database-Size</div>
        <div class="db_info_table_cell">
            <?php echo $pia_db_size;?>
        </div>
    </div>
    <div class="db_info_table_row">
        <div class="db_info_table_cell">last Modification</div>
        <div class="db_info_table_cell">
            <?php echo $pia_db_mod;?>
        </div>
    </div>
    <div class="db_info_table_row">
        <div class="db_info_table_cell">DB Backup</div>
        <div class="db_info_table_cell">
            <?php echo $Pia_Archive_count.' backups where found';?>
        </div>
    </div>
    <div class="db_info_table_row">
        <div class="db_info_table_cell">Scan Status (arp)</div>
        <div class="db_info_table_cell">
            <?php echo $pia_arpscans_result;?></div>
    </div>
</div>

<form method="post" action="maintenance.php">
<div class="db_info_table">
    <div class="db_info_table_row">
        <div class="db_info_table_cell" style="height:50px; text-align:center; vertical-align: middle;">
            <div style="display: inline-block; margin-right: 10px;">Theme Selection:</div>
            <div style="display: inline-block;">
                <select name="skinselector">
                    <option value="">--Choose a theme--</option>
                    <option value="skin-black-light">black light</option>
                    <option value="skin-black">black</option>
                    <option value="skin-blue-light">blue light</option>
                    <option value="skin-blue">blue</option>
                    <option value="skin-green-light">green light</option>
                    <option value="skin-green">green</option>
                    <option value="skin-purple-light">purple light</option>
                    <option value="skin-purple">purple</option>
                    <option value="skin-red-light">red light</option>
                    <option value="skin-red">red</option>
                    <option value="skin-yellow-light">yellow light</option>
                    <option value="skin-yellow">yellow</option>
                </select></div>
            <div style="display: inline-block;"><input type="submit" value="Set">
                <?php echo $pia_skin_test; ?>
            </div>
        </div>
    </div>
</div>
</form>

<div class="db_info_table">
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-green dbtools-button" id="btnPiaEnableDarkmode" style="border-top: solid 3px #00a65a;" onclick="askPiaEnableDarkmode()">Toggle Modes (Dark/Light)</button>
        </div>
        <div class="db_tools_table_cell_b" style="">Toggle between dark mode and light mode. If the switch does not work properly, try to clear the browser cache.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-yellow dbtools-button" id="btnPiaToggleArpScan" style="border-top: solid 3px #ffd080;" onclick="askPiaToggleArpScan()">Toggle arp-Scan (on/off)</button>
        </div>
        <div class="db_tools_table_cell_b" style="">Switching the arp-scan on or off. When the scan has been switched off it remains off until it is activated again. Active scans are not canceled.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-red dbtools-button" id="btnDeleteMAC" style="border-top: solid 3px #dd4b39;" onclick="askDeleteDevicesWithEmptyMACs()">Delete Devices with empty MACs</button>
        </div>
        <div class="db_tools_table_cell_b">Before using this function, please make a backup. The deletion cannot be undone. All devices without MAC will be deleted from the database.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-red dbtools-button" id="btnDeleteMAC" style="border-top: solid 3px #dd4b39;" onclick="askDeleteAllDevices()">Delete all Devices</button>
        </div>
        <div class="db_tools_table_cell_b">Before using this function, please make a backup. The deletion cannot be undone. All devices will be deleted from the database.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-red dbtools-button" id="btnDeleteUnknown" style="border-top: solid 3px #dd4b39;" onclick="askDeleteUnknown()">Delete (unknown) Devices</button>
        </div>
        <div class="db_tools_table_cell_b">Before using this function, please make a backup. The deletion cannot be undone. All devices named (unknown) will be deleted from the database.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-red dbtools-button" id="btnDeleteEvents" style="border-top: solid 3px #dd4b39;" onclick="askDeleteEvents()">Delete all Events (Reset Presence)</button>
        </div>
        <div class="db_tools_table_cell_b">Before using this function, please make a backup. The deletion cannot be undone. All events in the database will be deleted. At that moment the presence of all devices will be reset. This can lead to invalid sessions. 
        This means that devices are displayed as "present" although they are offline. A scan while the device in question is online solves the problem.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-red dbtools-button" id="btnPiaBackupDBtoArchive" style="border-top: solid 3px #dd4b39;" onclick="askPiaBackupDBtoArchive()">DB Backup</button>
        </div>
        <div class="db_tools_table_cell_b">The database backups are located in the database directory as a zip-archive, named with the creation date. There is no maximum number of backups.</div>
    </div>
    <div class="db_info_table_row">
        <div class="db_tools_table_cell_a" style="">
            <button type="button" class="btn btn-default pa-btn pa-btn-delete bg-red dbtools-button" id="btnPiaRestoreDBfromArchive" style="border-top: solid 3px #dd4b39;" onclick="askPiaRestoreDBfromArchive()">DB Restore<br><?php echo $latestbackup_date;?></button>
        </div>
        <div class="db_tools_table_cell_b">The latest backup can be restored via the button, but older backups can only be restored manually. After the restore, make an integrity check on 
            the database for safety, in case the db was currently in write access when the backup was created.</div>
    </div>
</div>


<div style="width: 100%; height: 20px;"></div>
    <!-- ----------------------------------------------------------------------- -->

</section>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<!-- ----------------------------------------------------------------------- -->
<?php
  require 'php/templates/footer.php';
?>


<script>

// delete devices with emty macs
function askDeleteDevicesWithEmptyMACs () {
  // Ask 
  showModalWarning('Delete Devices', 'Are you sure you want to delete all devices with empty MAC addresses?<br>(maybe you prefer to archive it)',
    'Cancel', 'Delete', 'deleteDevicesWithEmptyMACs');
}
function deleteDevicesWithEmptyMACs()
{ 
  // Delete device
  $.get('php/server/devices.php?action=deleteAllWithEmptyMACs', function(msg) {
    showMessage (msg);
  });
}

// delete all devices 
function askDeleteAllDevices () {
  // Ask 
  showModalWarning('Delete Devices', 'Are you sure you want to delete all devices?',
    'Cancel', 'Delete', 'deleteAllDevices');
}
function deleteAllDevices()
{ 
  // Delete device
  $.get('php/server/devices.php?action=deleteAllDevices', function(msg) {
    showMessage (msg);
  });
}

// delete all (unknown) devices 
function askDeleteUnknown () {
  // Ask 
  showModalWarning('Delete (unknown) Devices', 'Are you sure you want to delete all (unknown) devices?',
    'Cancel', 'Delete', 'deleteUnknownDevices');
}
function deleteUnknownDevices()
{ 
  // Execute
  $.get('php/server/devices.php?action=deleteUnknownDevices', function(msg) {
    showMessage (msg);
  });
}

// delete all Events 
function askDeleteEvents () {
  // Ask 
  showModalWarning('Delete Events', 'Are you sure you want to delete all Events?',
    'Cancel', 'Delete', 'deleteEvents');
}
function deleteEvents()
{ 
  // Execute
  $.get('php/server/devices.php?action=deleteEvents', function(msg) {
    showMessage (msg);
  });
}


// Backup DB to Archive 
function askPiaBackupDBtoArchive () {
  // Ask 
  showModalWarning('DB Backup', 'Are you sure you want to exectute the the DB Backup? Be sure that no scan is currently running.',
    'Cancel', 'Run Backup', 'PiaBackupDBtoArchive');
}
function PiaBackupDBtoArchive()
{ 
  // Execute
  $.get('php/server/devices.php?action=PiaBackupDBtoArchive', function(msg) {
    showMessage (msg);
  });
}


// Restore DB from Archive 
function askPiaRestoreDBfromArchive () {
  // Ask 
  showModalWarning('DB Restore', 'Are you sure you want to exectute the the DB Restore? Be sure that no scan is currently running.',
    'Cancel', 'Run Restore', 'PiaRestoreDBfromArchive');
}
function PiaRestoreDBfromArchive()
{ 
  // Execute
  $.get('php/server/devices.php?action=PiaRestoreDBfromArchive', function(msg) {
    showMessage (msg);
  });
}

// Restore DB from Archive 
function askPiaEnableDarkmode () {
  // Ask 
  showModalWarning('Switch Theme', 'After the theme switch, the page tries to reload itself to activate the change. If necessary, the cache must be cleared.',
    'Cancel', 'Switch', 'PiaEnableDarkmode');
}
function PiaEnableDarkmode()
{ 
  // Execute
  $.get('php/server/devices.php?action=PiaEnableDarkmode', function(msg) {
    showMessage (msg);
  });
}

// Toggle the Arp-Scans 
function askPiaToggleArpScan () {
  // Ask 
  showModalWarning('Toggle arp-Scan on or off', 'When the scan has been switched off it remains off until it is activated again.',
    'Cancel', 'Switch', 'PiaToggleArpScan');
}
function PiaToggleArpScan()
{ 
  // Execute
  $.get('php/server/devices.php?action=PiaToggleArpScan', function(msg) {
    showMessage (msg);
  });
}

</script>

