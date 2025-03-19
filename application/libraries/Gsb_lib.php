<?php
/**
 * Class : Bibliothèque offrant des fonctionnalités pour l'application GSB
 *  - gestion des connexions à l'application
 *  - gestion des dates
 *  - gestion des erreurs
 *  - validation des saisies    
 */
class Gsb_lib {

    protected $CI;

/**
 * Constructeur : 
 * Pour pouvoir utiliser les librairies de bases de CI, il faut récupérer l'instance en cours de CI
 */    
    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        setlocale(LC_TIME,"fr_FR.utf8","fra_fra");
    }

/**
 * Teste si un quelconque visiteur est connecté
 * @return vrai ou faux 
 */
    public function est_connecte(){
        return $this->CI->session->has_userdata('id');
    }

/**
 * Enregistre dans une variable session les infos d'un visiteur
 * @param $donnees contenant un tableau associatif avec l'id, le nom, le prénom 
*/
    public function connecter($donnees){
        $this->CI->session->set_userdata($donnees);
    }

/**
 * Détruit la session active
 */
    function deconnecter(){
        $this->CI->session->sess_destroy();
    }

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 
 * @param $madate au format  aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
 */
    public function date_vers_francais($maDate){
        @list($annee, $mois, $jour) = explode('-', $maDate);
        $date = $jour."/".$mois."/".$annee;
        return $date;
    }

/**
 * retourne le mois au format aaaamm selon le jour dans le mois
 * @param $date au format  jj/mm/aaaa
 * @return le mois au format aaaamm
 */
    public function get_id_mois($date = null){
        if ( ! isset($date) ){ //date du jour
            return date("Ym");
        }
        //TODO pour une date passée en paramètre (autre que la date du jour)
    }

/** 
 * Fournit le libellé en français correspondant à un numéro de mois.                     
 * Retourne une chaîne vide si le numéro n'est pas compris dans l'intervalle [1,12].
 * @param int numéro de mois
 * @return string le nom du mois en français
 */
    function get_nom_mois($unNoMois) {
        return utf8_encode(strftime("%B", strtotime("1900-".$unNoMois)));
    }

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs 
 * @param $msg : le libellé de l'erreur 
 */
    public function ajouter_erreur($msg){
        if ( ! isset($_REQUEST['erreurs']) ){
        $_REQUEST['erreurs'] = [];
    } 
        $_REQUEST['erreurs'][] = $msg;
    }

/**
* Retoune le nombre de lignes du tableau des erreurs 
* @return le nombre d'erreurs
*/
    public function nb_erreurs(){
        if ( ! isset($_REQUEST['erreurs']) ){
            return 0;
        }
        else{
            return count($_REQUEST['erreurs']);
        }
    }

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 
 * des message d'erreurs sont ajoutés au tableau des erreurs
 * @param $dateFrais 
 * @param $libelle 
 * @param $montant
 */
    function valide_infos_frais($dateFrais, $libelle, $montant){
        if($dateFrais === ""){  //TODO test des moins d'un an, validité de la date
            $this->ajouter_erreur("La date ne peut être vide");
        }
        if($libelle === ""){
            $this->ajouter_erreur("Le libellé ne peut être vide");
        }
        if($montant === ""){
            $this->ajouter_erreur("Le montant ne peut être vide");
        }
        elseif(is_numeric($montant) === FALSE){
            $this->ajouter_erreur("Le montant doit être numérique");
        }
        elseif($montant <= 0){
            $this->ajouter_erreur("Le montant doit être supérieur ou égal à 0");
        }
    }

/**
 * formatte un nombre a deux chiffres après la virgule 
 * @param $montant
 */
    function format_montant($montant){
        return number_format($montant, 2, ',', ' ');
    }

    function get_elts_menu($idRole) {
        $menu = [
            "v" => ["Gererfrais" => ["page" => "Gererfrais",
                                     "libelle" => "Saisie fiche de frais",
                                     "titre" => "Saisie fiche de frais",
                            ],
                    "Etatfrais" => ["page" => "Etatfrais",
                    "libelle" => "Mes fiche de frais",
                    "titre" => "Consultation de mes fiches de frais",
                                   ]
            
                        ],
            "c" => ["Validerfrais" => ["page" => "Validerfrais",
                                     "libelle" => "Validation fiche de frais",
                                     "titre" => "Validation des fiche de frais",
                            ],
                    "Rembourserfrais" => ["page" => "Rembourserfrais",
                    "libelle" => "Rembourser fiches de frais",
                    "titre" => "Remboursement des fiches de frais",
                                   ]
            
            ] 
           
                        ];
        return $menu[$idRole];

    }

}
?>