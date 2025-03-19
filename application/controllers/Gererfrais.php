<?php
/**
 * Class : Contrôleur permettant de gérer les frais d'un visiteur préalablement connecté.   
 *  - Crée la fiche du visiteur pour le mois en cours si elle n'existe pas déjà
 *  - Permet au visiteur de saisir et modifier ses frais forfaitaires
 *  - Permet au visiteur de visualiser, d'ajouter ou de supprimer des frais hors forfaits
 */
class Gererfrais extends CI_Controller {

    private $id_mois;
    private $id_visiteur;
    private $info = null; 
    
/**
 * Constructeur.   
 * si l'utilisateur n'est pas connecté il est redirigé vers le contrôleur de connexion.
 * sinon : 
 *  - chargement du modèle, des helpers et bibliothèques.
 *  - l'id du mois est initialisé au mois courant au format aaaamm.
 *  - l'id visiteur est initalisé à celui du visiteur connecté.
 */   
    public function __construct()
    {
        parent::__construct();
        $this->load->library('gsb_lib');
        $this->load->helper('url_helper');
        if( ! $this->gsb_lib->est_connecte() ){ 
           redirect(site_url('Connexion'));
        }
        else{
            $this->load->model('gsb_model');
            $this->load->helper('form_helper');
            $this->load->helper('html_helper');
            $this->load->library('session');
            $this->id_mois = $this->gsb_lib->get_id_mois();
            $this->id_visiteur = $this->session->id;
        }
    }

/**
 * méthode action par défaut : le visiteur accède à ce contrôleur en ayant cliqué sur le menu correspondant.  
 *  - usage : <application base url>/Gererfrais ou <application base url>/Gererfrais/index
 */
    public function index(){
        if($this->gsb_model->est_premier_frais_mois($this->id_visiteur, $this->id_mois)){
            $this->gsb_model->cree_nouvelles_lignes_frais($this->id_visiteur, $this->id_mois);
            $this->info = "Une nouvelle fiche de frais vient d'être créée pour le mois en cours";
        }
        $this->commun();
    }

/**
 * méthode action : le visiteur vient de cliquer sur le bouton valider de la partie frais forfaitaire.  
 *  - usage : <application base url>/Gererfrais/valider_maj_fraisforfait
 */    
    public function valider_maj_fraisforfait(){
        $lesFrais = $this->input->post('lesFrais');
        $this->gsb_model->maj_frais_forfait($this->id_visiteur, $this->id_mois, $lesFrais);
        $this->info = "Les modifications des frais forfaitisés ont bien été effectuées";
        $this->commun();
    }

/**
 * méthode action : le visiteur vient de cliquer sur le bouton ajouter de la partie nouvel élément hors forfait.  
 *  - usage : <application base url>/Gererfrais/valider_creation_fraishorsforfait
 */
    public function valider_creation_fraishorsforfait(){
        $dateFrais = $this->input->post("txtDateHF");
		    $libelle = $this->input->post("txtLibelleHF");
        $montant = $this->input->post("txtMontantHF");
        //TODO gérer les vérifications de formulaire par le navigateur
        $this->gsb_lib->valide_infos_frais($dateFrais, $libelle, $montant);
        if($this->gsb_lib->nb_erreurs() == 0){
            $this->gsb_model->creer_nouveau_frais_hors_forfait($this->id_visiteur, $this->id_mois, $libelle, $dateFrais, $montant);
            $this->info = "La création d'un frais hors-forfait a bien été effectuée";
        }
        $this->commun();
    }

/**
 * méthode action : le visiteur vient de cliquer sur l'icone permettant de supprimer un élément hors forfait.  
 *  - usage : <application base url>/Gererfrais/supprimer_fraishorsforfait/<id_fraishorsforfait>
 *  - TODO : securiser la suppression
 */
    public function supprimer_fraishorsforfait($id_fraishorsforfait){ 
        $this->gsb_model->supprimer_frais_hors_forfait($id_fraishorsforfait);
        $this->info = "La suppression du frais hors-forfait a bien été effectuée";
        $this->commun();
    }

    
/**
 * Traitement commun au contrôleur Gererfrais.
 */
    private function commun(){
        //infos générales page
        $this->load->view('structures/v_page_entete');
        $data["eltsMenu"] = $this->gsb_lib->get_elts_menu($this->session->idRole);
        $this->load->view('v_sommaire', $data);
        $num_annee = substr($this->id_mois, 0, 4);
        $num_mois = substr($this->id_mois, 4, 2);
        $date_titre = $this->gsb_lib->get_nom_mois($num_mois)." ".$num_annee; 
        $data['titre'] = "Renseigner ma fiche de frais du mois de ". $date_titre;
        $this->load->view('structures/v_contenu_entete', $data);
        
        //gestion des notifications
        if( isset($this->info) ){
            $data['info'] = $this->info;
            $this->load->view('structures/v_notification', $data);
        }

        //gestion des erreurs
        if($this->gsb_lib->nb_erreurs() > 0){
            $this->load->view('errors/html/v_error_gsb', $data);
        }

        //gestion des éléments forfaitisés
        $data['sc_titre'] = 'Eléments forfaitisés';
        $this->load->view('structures/v_souscontenu_entete', $data);
        $data['ff'] = $this->gsb_model->get_les_frais_forfait($this->id_visiteur, $this->id_mois);
        $data['action'] = "Gererfrais/valider_maj_fraisforfait";
        $data['label'] = "Valider";
        $this->load->view('v_fraisforfait_edit', $data);
        $this->load->view('structures/v_souscontenu_pied');

        //gestion Eléments hors forfait
        $data['sc_titre'] = 'Eléments hors forfait';
        $this->load->view('structures/v_souscontenu_entete', $data);
        //gestion descriptifs des éléments
        $data['fhf'] = $this->gsb_model->get_les_frais_hors_forfait($this->id_visiteur, $this->id_mois);
        $this->load->view('v_fraishorsforfait_table_sup', $data);
        //gestion nouvel élément
        $this->load->view('v_fraishorsforfait_edit.php');
        $this->load->view('structures/v_souscontenu_pied');
        
        //fin du contenu et de la page
        $this->load->view('structures/v_page_pied');
    }
}