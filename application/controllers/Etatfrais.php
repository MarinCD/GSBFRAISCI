<?php
/**
 * Class : Contrôleur permettant de visualiser l'état des fiches de frais d'un visiteur préalablement connecté.   
 * Permet au visiteur de :
 *  - choisir une fiche de frais en fonction du mois
 *  - visualiser l'état de la fiche selectionnée
 *  - visualiser les frais forfaitaires et hors-forfaits de la fiche selectionnée
 */
class Etatfrais extends CI_Controller {

    private $id_mois;
    private $id_visiteur;

/**
 * Constructeur   
 * si l'utilisateur n'est pas connecté il est redirigé vers le contrôleur de connexion.
 * sinon :
 *  - chargement du modèle, des helpers et bibliothèques
 *  - l'id visiteur est initalisé à celui du visiteur connecté
 *  - l'id du mois n'est pas initialisé (il le sera en fonction des actions de l'utilisateur)
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
            $this->id_visiteur = $this->session->id;
        }
    }

/**
 * méthode action par défaut : le visiteur accède à ce contrôleur en ayant cliqué sur le menu correspondant.  
 *  - usage : <application base url>/Etatfrais ou <application base url>/Etatfrais/index
 */
    public function index(){
        $this->id_mois = null;
        $this->commun();
    }

/**
 * méthode action : le visiteur vient de choisir un mois dans la liste déroulante.
 *  - usage : <application base url>/Gererfrais/valider_maj_fraisforfait
 */ 
    public function selectionner_mois(){
        $this->id_mois = $this->input->post('lstMois');
        $this->commun();
    }

/**
 * Traitement commun au contrôleur Etatfrais.
 */
    private function commun(){
        //infos générales page
        $this->load->view('structures/v_page_entete');
        $data["eltsMenu"] = $this->gsb_lib->get_elts_menu($this->session->idRole);
        $this->load->view('v_sommaire', $data);
        $data['titre'] = "Mes fiches de frais";
        $this->load->view('structures/v_contenu_entete', $data);
        
        //récupération des mois où l'utilisateur à des fiches de frais
        $les_mois = $this->gsb_model->get_les_mois_disponibles($this->id_visiteur);
        if(count($les_mois) == 0){
            $this->gsb_lib->ajouter_erreur("Aucune fiche de frais n'a été saisie pour ce visiteur");
            $this->load->view('errors/html/v_error_gsb');
        }
        else{
            if ( ! isset($this->id_mois) ){  // si aucun mois choisi, on prend par défaut le premier mois dans la liste
                $this->id_mois = $les_mois[0]['mois'];
            }

            //gestion liste déroulante
            $options = []; // création d'un tableau contenant les 'options' de la liste 'select'
            foreach ($les_mois as $un_mois){
                $libelle = $this->gsb_lib->get_nom_mois($un_mois['numMois'])." ".$un_mois['numAnnee'];
                $options[$un_mois['mois']] = $libelle; // <option value=$un_mois['mois']> $libelle </>
            }
            $data['lst_contenu'] = $options;
            $data['lst_select'] = $this->id_mois;  // correspondant à l'élément selectionné dans la liste (attribut selected pour un option)
            $data['lst_action'] = 'Etatfrais/selectionner_mois'; //action effectuée par le formulaire un fois soummis
            $data['lst_id'] = 'lstMois';
            $data['lst_label'] = 'Mois';
            $data['sc_titre'] = 'Mois à sélectionner :';
            $this->load->view('structures/v_souscontenu_entete', $data);
            $this->load->view('templates/v_liste_deroulante', $data);
            $this->load->view('structures/v_souscontenu_pied');

            //gestion de la fiche
            $num_annee = substr($this->id_mois, 0, 4);
            $num_mois = substr($this->id_mois, 4, 2);
            $date_titre = $this->gsb_lib->get_nom_mois($num_mois)." ".$num_annee;
            $data['sc_titre'] = 'Fiche de frais du mois de '.$date_titre.' :';
            $this->load->view('structures/v_souscontenu_entete', $data);
            //gestion zone Etat
            $data['fiche'] = $this->gsb_model->get_les_infos_ficheFrais($this->id_visiteur, $this->id_mois);
            $this->load->view('v_etat_fiche', $data);
            //gestion frais forfaits
            $data['ff'] = $this->gsb_model->get_les_frais_forfait($this->id_visiteur, $this->id_mois);
            $this->load->view('v_fraisforfait_table', $data);
            //gestion frais hors forfaits
            $data['fhf'] = $this->gsb_model->get_les_frais_hors_forfait($this->id_visiteur, $this->id_mois);
            $this->load->view('v_fraishorsforfait_table', $data);
            //fin de la fiche
            $this->load->view('structures/v_souscontenu_pied');

            //fin du contenu et de la page
            $this->load->view('structures/v_page_pied');
        }
    }
}