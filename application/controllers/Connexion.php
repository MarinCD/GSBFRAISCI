<?php
/**
 * Class : Contrôleur permettant à un visiteur de se connecter à l'application GSBFrais.   
 * Permet au visiteur de rentrer son login et mot de passe. 
 *  - Si l'accès est validé il est redirigé vers la page d'acceuil de l'intranet
 *  - Sinon il est invité à ressaisir son login et mot de passe
 */
class Connexion extends CI_Controller {

    private $info = null; 

/**
 * Constructeur   
 * Chargement du modèle, des helpers et bibliothèques
 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gsb_model');
        $this->load->helper('url_helper');
        $this->load->helper('form_helper');
        $this->load->library('session');
        $this->load->library('gsb_lib');
    }

/**
 * méthode action par défaut : le visiteur accède au site ou est redirigé vers cette page
 *  - usage : <application base url>/Connexion ou <application base url>/Connexion/index ou <url de l'application> si routage par défaut defini vers cette page
 */
    public function index(){
        $this->commun();
    }

/**
 * méthode action : le visiteur vient de cliquer sur le bouton valider du formulaire de connexion  
 *  - usage : <application base url>/Connexion/valider_connexion
 */ 
    public function valider_connexion(){
        $login = $this->input->post('txtLogin');
        $mdp = $this->input->post('pwdMdp');
        $utilisateur = $this->gsb_model->get_infos_utilisateur($login, $mdp);
        var_dump($utilisateur);
        if( is_array($utilisateur) ){ //le login+mot de passe existe, le visiteur peut accéder à l'application
            $donnees = array(
                'id' => $utilisateur['idUtilisateur'],
                'nom' => $utilisateur['nom'],
                'prenom' => $utilisateur['prenom'],
                'idRole' => $utilisateur['idRole'],
                'libelleRole' => $utilisateur['libelleRole']


            );
            $this->gsb_lib->connecter($donnees);
            redirect(site_url('Accueil'));
        }
        else{
            $this->gsb_lib->ajouter_erreur("Login ou mot de passe incorrect");
            $this->commun();
        }
    }

/**
 * méthode action : le visiteur vient de cliquer sur le bouton deconnexion du menu  
 *  - usage : <application base url>/Connexion/deconnexion
 */ 
    public function deconnexion(){
        $this->gsb_lib->deconnecter();
        $this->info = "vous avez bien été deconnecté de votre session";
        $this->commun();
    }

/**
 * Traitement commun au contrôleur Connexion.
 */
    private function commun(){
        //infos générale page
        $this->load->view('structures/v_page_entete');
        
        //gestion des notifications
        if( isset($this->info) ){
           $data['info'] = $this->info;
           $this->load->view('structures/v_notification', $data);
        }

        //gestion des erreurs
        if($this->gsb_lib->nb_erreurs() > 0){
            $this->load->view('errors/html/v_error_gsb');
        }

        //formulaire de connexion
        $this->load->view('v_connexion');

        //fin de la page
        $this->load->view('structures/v_page_pied');
    }
}