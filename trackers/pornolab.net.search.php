<?php
include_once('pornolab.net.engine.php');

$classInfo = ['class_name'   => 'pornolabSearch',];

return $classInfo;

class pornolabSearch extends pornolab
{
	//ищем темы пользователя
	public static function mainSearch($user_id, $tracker, $user)
	{
		$cookie = Database::getCookie($tracker);
		if (pornolab::checkCookie($cookie))
		{
			pornolab::$sess_cookie = $cookie;
			//запускам процесс выполнения
			pornolab::$exucution = TRUE;
		}
		else
    		pornolab::getCookie($tracker);

		if (pornolab::$exucution)
		{
    		$user = iconv('utf-8', 'windows-1251', $user);
    		$page = Sys::getUrlContent(
            	array(
            		'type'           => 'POST',
            		'header'         => 1,
            		'returntransfer' => 1,
            		'url'            => 'http://pornolab.net/forum/tracker.php',
            		'cookie'         => pornolab::$sess_cookie,
            		'postfields'     => 'prev_my=0&prev_new=0&prev_oop=0&f%5B%5D=-1&o=1&s=2&tm=-1&pn='.$user.'&nm=&submit=%CF%EE%E8%F1%EA',
            		'convert'        => array('windows-1251', 'utf-8//IGNORE'),
            	)
	        );

	        if ( ! empty($page))
	        {
	        	//сбрасываем варнинг
				Database::clearWarnings($tracker);

	    		preg_match_all('/<a class=\"gen f\" href=\"tracker\.php\?f=\d{1,9}\">(.*)<\/a>/', $page, $section);
	    		preg_match_all('/<a class=\"med tLink bold\" href=\"\.\/viewtopic.php\?t=(\d{3,9})\">(.*)<\/a>/', $page, $threme);
	
	    		for ($i=0; $i<count($threme[1]); $i++)
	    			Database::addThremeToBuffer($user_id, $section[1][$i], $threme[1][$i], $threme[2][$i], $tracker);
	    	}

    		$toDownload = Database::takeToDownload($tracker);
    		if (count($toDownload) > 0)
    		{
                for ($i=0; $i<count($toDownload); $i++)
                {
                	//сбрасываем варнинг
					Database::clearWarnings($tracker);
                    //сохраняем торрент в файл
                    $id = $toDownload[$i]['id'];
                    $torrent_id = $toDownload[$i]['threme_id'];
                    $name = $toDownload[$i]['threme'];
                    $torrent = Sys::getUrlContent(
                    	array(
                    		'type'           => 'POST',
                    		'returntransfer' => 1,
                    		'url'            => 'http://pornolab.net/forum/dl.php?t='.$torrent_id,
                    		'cookie'         => pornolab::$sess_cookie.'; bb_dl='.$torrent_id,
                    		'sendHeader'     => array('Host' => 'pornolab', 'Content-length' => strlen(pornolab::$sess_cookie.'; bb_dl='.$torrent_id)),
                    		'referer'        => 'http://pornolab.net/forum/viewtopic.php?t='.$torrent_id,
                    	)
                    );
    				$message = $toDownload[$i]['threme'].' добавлена для скачивания.';
    				$status = Sys::saveTorrent($toDownload[$i]['tracker'], $toDownload[$i]['threme_id'], $torrent, $toDownload[$i]['threme_id'], 0, $message, date('d M Y H:i'));
								
					if ($status == 'add_fail' || $status == 'connect_fail' || $status == 'credential_wrong')
					{
					    $torrentClient = Database::getSetting('torrentClient');
					    Errors::setWarnings($torrentClient, $status);
					}	                            
                    
    				//обновляем время регистрации торрента в базе
    				Database::setDownloaded($toDownload[$i]['id']);
                }
            }
        }
	}
}
?>