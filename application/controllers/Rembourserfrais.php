<?php
/**
 * Class : Contrôleur permettant de visualiser l'état des fiches de frais d'un visiteur préalablement connecté.   
 * Permet au visiteur de :
 *  - choisir une fiche de frais en fonction du mois
 *  - visualiser l'état de la fiche selectionnée
 *  - visualiser les frais forfaitaires et hors-forfaits de la fiche selectionnée
 */
class Rembourserfrais extends CI_Controller {

    private $id_mois;
    private $id_visiteur;
    private $id_fiche;
    private $info = null;

/**
 * Constructeur   
 * si l'utilisateur n'est pas connecté il est redirigé vers le contrôleur de connexion.
 * sinon :
 *  - chargement du modèle, des helpers et bibliothèques
 *  - l'id visiteur est initalisé à celui du visiteur connecté
 *  - l'id du mois et du visiteur n'est pas initialisé (il le seront en fonction des actions du comptable)
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
            $this->load->library('session');
        }
    }

/**
 * méthode action par défaut : le comptable accède à ce contrôleur en ayant cliqué sur le menu correspondant.  
 *  - usage : <application base url>/Rembourserfrais ou <application base url>/Rembourserfrais/index
 */
    public function index(){
        $this->id_visiteur = null;
        $this->id_mois = null;
        $this->id_fiche = null;
        $this->commun();
    }

/**
 * méthode action : le comptable vient de choisir une fiche dans la liste déroulante.
 *  - usage : <application base url>/Rembourserfrais/selectionner_fiche
 */ 
    public function selectionner_fiche(){
        $this->id_fiche = $this->input->post('lstFiches');
        list($this->id_visiteur, $this->id_mois) = explode('_', $this->id_fiche);
        $this->commun();
    }

    /**
 * méthode action : le comptable vient de cliquer sur le bouton de validation.
 *  - usage : <application base url>/Rembourserfrais/selectionner_fiche
 */ 
public function rembourser_fiche(){
    //recuperer l'id de la fiche a rembourser
    $id_fiche = $this->input->post('idFiche');
    //var_dump($_POST);
    list($id_visiteur, $id_mois) = explode('_', $id_fiche);
    //traiter la fiche à rembourser
    $this -> gsb_model -> maj_etat_fiche_frais($id_visiteur, $id_mois, "RB");
    $this->info = "La fiche de frais de".$id_visiteur." a bien été remboursée";
    
    //prevoir la prochaine fiche à visualiser dans l'interface
    $this->id_visiteur = null;
    $this->id_mois = null;
    $this->id_fiche = null;
    $this->commun();
}

/**
 * Traitement commun au contrôleur Rembourserfrais.
 */
    private function commun(): void{
        //infos générales page
        $this->load->view('structures/v_page_entete');
        $data["eltsMenu"] = $this->gsb_lib->get_elts_menu($this->session->idRole);
        $this->load->view('v_sommaire', $data);
        $data['titre'] = "Remboursement des fiches de frais";
        $this->load->view('structures/v_contenu_entete', $data);
         //gestion des notifications
         if( isset($this->info) ){
            $data['info'] = $this->info;
            $this->load->view('structures/v_notification', $data);
        }
        //récupération des fiches de frais dans l'état VA 
        $les_fiches = $this->gsb_model->get_les_fiche_etat("VA");
        if(count($les_fiches) == 0){
           //notification toutes les fiches de frais ont été remboursés
           $data['info'] = "Toutes les fiches de frais ont été remboursées";
           $this->load->view('structures/v_notification', $data);

        }
        else{
            if ( ! isset($this->id_mois) ){  // si aucun mois choisi, on prend par défaut la première fiche 
                $this->id_mois = $les_fiches[0]['mois'];
                $this->id_visiteur = $les_fiches[0]['idVisiteur'];
                $this->id_fiche =  $this->id_visiteur.'_'.$this->id_mois;
            }
           
            //gestion liste déroulante
            $options = []; // création d'un tableau contenant les 'options' de la liste 'select'
            foreach ($les_fiches as $une_fiche){
                $idFiche = $une_fiche['idVisiteur']. '_'.$une_fiche['mois'];
                $libelle = $une_fiche['nom']." - ".$une_fiche['prenom']." - ".$this->gsb_lib->get_nom_mois($une_fiche['numMois'])." - ".$une_fiche['numAnnee'];
                $options[$idFiche] = $libelle; // <option value=$un_mois['mois']> $libelle </>
            }
            $data['lst_contenu'] = $options;
            $data['lst_select'] = $this->id_fiche;  // correspondant à l'élément selectionné dans la liste (attribut selected pour un option)
            $data['lst_action'] = 'Rembourserfrais/selectionner_fiche'; //action effectuée par le formulaire un fois soummis
            $data['lst_id'] = 'lstFiches';
            $data['lst_label'] = 'Fiches';
            $data['sc_titre'] = 'Fiche à sélectionner :';

            

            $this->load->view('structures/v_souscontenu_entete', $data);
            
            
            
            $this->load->view('templates/v_liste_deroulante', $data);
            $this->load->view('structures/v_souscontenu_pied');

 

            //gestion de la fiche
            $num_annee = substr($this->id_mois, 0, 4);
            $num_mois = substr($this->id_mois, 4, 2);
            $le_visiteur =  $this->gsb_model->get_detail_visiteur($this->id_visiteur);
            $date_titre = $this->gsb_lib->get_nom_mois($num_mois)." ".$num_annee;
            $visiteur_titre = $le_visiteur['prenom']. ' '. $le_visiteur['nom'];
            $data['sc_titre'] =  'Fiche de frais de '.$visiteur_titre.' du mois de '.$date_titre.' :';
            $this->load->view('structures/v_souscontenu_entete', $data);
            //gestion zone Etat
            $fiche = $this->gsb_model->get_les_infos_ficheFrais($this->id_visiteur, $this->id_mois);
            $fiche['libelle'] = "Montant validé";
            $data['fiche'] = $fiche;
            $this->load->view('v_etat_fiche', $data);
            
            //gestion frais forfaits
            $data['ff'] = $this->gsb_model->get_les_frais_forfait($this->id_visiteur, $this->id_mois);
            $this->load->view('v_fraisforfait_table', $data);
            //gestion frais hors forfaits
            $data['fhf'] = $this->gsb_model->get_les_frais_hors_forfait($this->id_visiteur, $this->id_mois);
            $this->load->view('v_fraishorsforfait_table', $data);
            
           

            //gestion de l'acion rembourser
            $data['action'] = "Rembourserfrais/rembourser_fiche";
            $data['boutonLabel'] = "Rembourser la fiche";
            $this->load->view('v_action', $data);

            
            //fin de la fiche
            $this->load->view('structures/v_souscontenu_pied');

            //fin du contenu et de la page
            $this->load->view('structures/v_page_pied');
        }
    }
}