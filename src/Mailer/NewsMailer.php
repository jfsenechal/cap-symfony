<?php

namespace Cap\Commercio\Mailer;

use Cap\Commercio\Helper\DateHelper;
use Cap\Commercio\Helper\StringHelper;
use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\NewsRepository;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NewsMailer
{
    private string $senderName = 'Cap sur Marche';
    private string $senderEmail = 'info@capsurmarche.com';

    public function __construct(
        private readonly CommercioCommercantRepository $commercioCommercantRepository,
        private readonly CommercioBottinRepository $commercioBottinRepository,
        private readonly NewsRepository $newsRepository,
        private readonly ParameterBagInterface $parameterBag,
        private readonly MandrillMail $mandrillMail
    ) {
        define('PREFIX', 'https://cap.marche.be'); //url site
        define('PREFIX_RESOURCES', ''); // vide
        define('TEMPLATES_PATH', '/templates/');
        define('TEMPLATES_FOLDER_NAME', 'commercio');
        define('MANDRILL_SUBACCOUNT', 'commercio');

        $this->api = $this->parameterBag->get('MANDRILL_API');
    }

    public function send(): void
    {
        try {

            $allNews = $this->newsRepository->findNotSended();
            dump($allNews);
            foreach ($allNews as $news) {

                if (!$news->isSendByMail()) {
                    continue;
                }

                $bottins = [];
                if ($news->isSendToBottin()) {
                    $commercants = $this->commercioCommercantRepository->findCanReceiveNews();
                    $bottins = $this->commercioBottinRepository->findAllOrdered();
                } else {
                    $commercants = $this->commercioCommercantRepository->findMemberAndCanReceiveNews();
                }

                $new = [];
                $new['news_title'] = $news->getTitle();

                //traitement sur la news
                $new["pretty_date"] = DateHelper::formatDateTime($news->getInsertDate());
                $new["description_html"] = StringHelper::parse($news->getDescription());
                //send mail
                $mandrillMail = $this->mandrillMail;
                //$mandrillMail = new MandrillMail(MANDRILL_APIKEY);
                $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';
                $mandrillMail->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
                $mandrillMail->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
                $mandrillMail->addMailDataItem(new MandrillMailDataItem("NEWS", $new));
                $mandrillMail->template = "commercio_news";
                $mandrillMail->subject = $news->getTitle();

                $mandrillMail->senderName = $this->senderName;
                $mandrillMail->senderEmail = $this->senderEmail;

                //Si on envoie a tout les commerçant du bottin
                if ($news->isSendToBottin()) {

                    foreach ($bottins as $bottin) {

                        $bot = $bottin->getBottin();
                        $fiche = $this->commercioCommercantRepository->findByIdCommercant($bottin->getCommercantId());
                        if (!$fiche) {
                            continue;
                        }
                        if ($fiche->isIsMember()) {
                            continue;
                        }

                        $nom = "";

                        if ($bot["prenom"]) {
                            $prenom = htmlspecialchars_decode($bot["prenom"]);

                            if ($bot["nom"]) {
                                $nom = htmlspecialchars_decode($bot["nom"]);
                            }
                        } else {
                            if ($bot["nom"]) {
                                $prenom = htmlspecialchars_decode($bot["nom"]);
                            } else {
                                $prenom = htmlspecialchars_decode($bot["title"]);
                            }
                        }

                        if ($this->parameterBag->get('kernel.environment') == 'dev') {
                            $mandrillMail->addReceiver("jf@marche.be", $prenom, $nom);
                            break;
                        } else {
                            $bot['email'] = "jf@marche.be";
                            $mandrillMail->addReceiver($bot["email"], $prenom, $nom);
                            break;
                        }
                    }

                }
                $commercants = [];
                foreach ($commercants as $commercant) {

                    if ($this->parameterBag->get('kernel.environment') == 'dev') {
                        $mandrillMail->addReceiver(
                            "jf@marche.be",
                            $commercant->getLegalFirstname(),
                            $commercant->getLegalLastname()
                        );
                        break;

                    } else {
                        $mandrillMail->addReceiver(
                            $commercant->getLegalEmail(),
                            $commercant->getLegalFirstname(),
                            $commercant->getLegalLastname()
                        );

                        if ($commercant->getLegalEmail2()) {
                            $mandrillMail->addReceiver(
                                $commercant->getLegalEmail2(),
                                $commercant->getLegalFirstname(),
                                $commercant->getLegalLastname()
                            );

                        }
                    }
                }

                try {
                    if ($this->parameterBag->get('kernel.environment') == 'dev') {
                        //var_dump($mandrillMail);
                          $mandrillMail->sendMe();
                    } else {
                        //Vu qu'il y a plus de 1200 commerçant on envoie que si on est en prod
                        // $mandrillMail->sendMe();
                    }
                } catch (Exception $exception) {
                    //TODO : gestion des exceptions
                    dump($exception->getMessage());
                }

            }

            //set as send
            $news->setIsSend(true);

        } catch (Exception $exception) {
            //mail("jdusart@appandweb.be","test3",json_encode($exception));
            throw $exception;
        }

        // $this->newsRepository->flush();
    }
}