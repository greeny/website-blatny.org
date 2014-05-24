<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Model;

/**
 * @property int $id
 * @property User $user m:hasOne
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $time
 * @property bool $published
 */
class Article extends BaseEntity
{

}
