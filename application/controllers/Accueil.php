<?php
/**
 * Class : Contrôleur permettant de visualiser l'accueil de l'intranet pour un visiteur préalablement connecté.   
 */
class Accueil extends CI_Controller {

/**
 * Constructeur.   
 * si l'utilisateur n'est pas connecté il est redirigé vers le contrôleur de connexion. 
 * sinon il peut visualiser la page d'accueil de l'intranet.
 */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('gsb_lib');
        $this->load->helper('url_helper');
        $this->load->helper('html_helper');
        if( ! $this->gsb_lib->est_connecte() ){
            redirect(site_url('Connexion'));
        }
        else{
            $this->load->library('session');
        }
        $this->load->model('gsb_model');
    }

/**
 * méthode action par défaut : le visiteur accède à ce contrôleur en ayant cliqué sur le menu correspondant 
 * ou en ayant été redirigé vers ce contrôleur
 *  - usage : Accueil ou Accueil/index
 */
    public function index(){

        
        //infos générale page
        $this->load->view('structures/v_page_entete');
        $data["eltsMenu"] = $this->gsb_lib->get_elts_menu($this->session->idRole);
        $this->load->view('v_sommaire', $data);
        $data['titre'] = "Bienvenue sur l'intranet GSB";
        $this->load->view('structures/v_contenu_entete', $data);

        $this->load->view('templates/v_en_travaux', $data);
       
        //fin de la page
        $this->load->view('structures/v_page_pied');
    }
}