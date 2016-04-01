<?php
set_time_limit(0);
while(1){
    echo exec("php exec.php Home Push");
    usleep(1000000);
}
