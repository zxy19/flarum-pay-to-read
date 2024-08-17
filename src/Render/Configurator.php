<?php
namespace Xypp\PayToRead\Render;

use DOMElement;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Database\AbstractModel;
use Flarum\Http\RequestUtil;
use Flarum\Post\CommentPost;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Guest;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Config\Util\XmlUtils;
use Xypp\PayToRead\Utils\TagPicker;
use Symfony\Contracts\Translation\TranslatorInterface;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\Payment;

class Configurator
{
    private $translator;
    protected $settings;
    public function __construct(SettingsRepositoryInterface $settings, TranslatorInterface $translator)
    {
        $this->settings = $settings;
        $this->translator = $translator;
    }
    public function __invoke(\s9e\TextFormatter\Configurator $config)
    {
        $config->BBCodes->addCustom(
            '[pay amount={UINT} id={UINT}]{TEXT}[/pay]',
            '<div class="ptr-block ptr-preview" data-amount="{@amount}" data-id="{@id}">
            <xsl:apply-templates />
            </div>'
        );
        $unpaidTag = $config->tags->add('ptrunpaid');
        $unpaidTag->template =
            '<div class="ptr-block ptr-render ptr-payment-require" data-id="{@id}" data-amount="{@amount}" data-haspaid-cnt="{@paid}"></div>';
        $paidTag = $config->tags->add('ptrpaid');
        $paidTag->template =
            '<div class="ptr-block ptr-render ptr-paid" data-id="{@id}" data-amount="{@amount}" data-haspaid-cnt="{@paid}"><xsl:apply-templates /></div>';
        $paidTag = $config->tags->add('ptrnotfound');
        $paidTag->template =
            '<div class="ptr-block ptr-render ptr-notfound" data-id="{@id}" data-amount="{@amount}"></div>';

    }
}