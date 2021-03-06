<?php

defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * 新鲜事删除扩展服务接口--删除回复源内容.
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 *
 * @version $Id: PwDeleteFreshDoReplyDelete.php 21189 2012-11-30 04:00:34Z xiaoxia.xuxx $
 */
class PwDeleteFreshDoReplyDelete extends iPwGleanDoHookProcess
{
    public $record = [];

    public function gleanData($value)
    {
        if ($value['type'] == PwFresh::TYPE_THREAD_REPLY) {
            $this->record[] = $value['src_id'];
        }
    }

    public function run($ids)
    {
        if ($this->record) {
            $srv = new PwDeleteReply(new PwFetchReplyByPid($this->record), $this->srv->user);
            $srv->setIsDeleteFresh(false)
                ->setIsDeductCredit($this->srv->isDeductCredit)
                ->execute();

            //帖子回复产生的新鲜事删除，日志记录为“删除帖子”类型的日志
            Wekit::load('log.srv.PwLogService')->addThreadManageLog($this->srv->user, 'delete', $srv->data, '');
        }
    }
}
