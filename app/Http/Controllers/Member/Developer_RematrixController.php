<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_membership;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_membership_package;
use Crypt;
use Request;
class Developer_RematrixController extends Member
{
	public function index()
	{
		$shop_id 			= $this->user_info->shop_id;
		$data["slot_count"] = Tbl_mlm_slot::where("shop_id",$shop_id)->count() ? Tbl_mlm_slot::where("shop_id",$shop_id)->count() : 0;
		return view('member.developer.rematrix',$data);
	}
	public function simulate()
	{
		$shop_id 			= $this->user_info->shop_id;
		$data = [];
		$data['membership'] = Tbl_membership::where('shop_id', $shop_id)->where('membership_archive', 0)
		->join('tbl_membership_package', 'tbl_membership_package.membership_id', '=', 'tbl_membership.membership_id')->get();
		$data['customers'] = $this->dummy_account(); //200
		return view('member.developer.simulate.index', $data);
	}
	public function simulate_submit()
	{
		// return $_POST;
		$shop_id 			= $this->user_info->shop_id;
		$membership_id = Request::input('membership_id');
		$no_of_customer = Request::input('no_of_customer');
		$no_of_slots_customer = Request::input('no_of_slots_customer');
		$no_of_slots = Request::input('no_of_slots');
		$no_of_downlines = Request::input('no_of_downlines');

		if(isset($membership_id))
		{
			$membership = [];
			$count = 1;
			foreach($membership_id as $key => $value)
			{
				$membership[$count] = $key;
				$count++;
			}
			// $this->	
		}
		else
		{
			$data['status'] = 'warning';
			$data['error'][0] = 'Membership is Required';
			
		}
		return json_encode($data);
		// if(isset())
	}
	public function reset()
	{
		
	}
	public function generate_customer($shop_id, $count)
	{
		$customers = $this->dummy_account();
		foreach ($customers as $key => $value) 
		{
			$insert[$key]['shop_id'] = $shop_id;
	        $insert[$key]['first_name'] = $value['first_name'];
	        $insert[$key]['last_name'] = $value['last_name'];
	        $insert[$key]['email'] = $value['email'];
	        $insert[$key]['password'] = Crypt::encrypt('water123');
	        $insert[$key]['company'] = 'DIGIMA WEB SOLUTIONS';
	        $insert[$key]['created_date'] = Carbon::now();
	        $insert[$key]['IsWalkin'] = 0;
	        $insert[$key]['ismlm'] = 1;
	        $insert[$key]['mlm_username'] = 'username_'.$key;
	        $insert[$key]['country_id'] = 420;
	        $insert[$key]['tin_number'] = 'XXX-XX-XXXX';
		}
		Tbl_customer::insert($insert);
	}
	public function fix_all_customer_info($shop_id)
	{
		$all_customer = Tbl_customer::where('shop_id', $shop_id)->get();
		foreach ($all_customer as $key => $value) 
		{
			$customer_id = $value->customer_id;

			$insertSearch['customer_id'] = $customer_id;
	        $insertSearch['body'] = $value->title_name.' '.$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix_name.' '.$value->$email.' '.$value->company;
	        Tbl_customer_search::insert($insertSearch);

	        $insert_other['customer_id'] = $customer_id;
        	DB::table('tbl_customer_other_info')->insert($insert_other);

        	$insert_address[0]['customer_id'] = $customer_id;
	        $insert_address[0]['purpose'] = 'billing';
	        $insert_address[0]['country_id'] = 420;
	        $insert_address[1]['customer_id'] = $customer_id;
	        $insert_address[1]['purpose'] = 'shipping';
	        $insert_address[1]['country_id'] = 420;
	        DB::table('tbl_customer_address')->insert($insert_address);  
		
		}                          
	}
	public function dummy_account()
	{
		$data = array(
			array("first_name"=>"Garrett","last_name"=>"Chelsea","email"=>"consectetuer.euismod@vitae.edu"),
			array("first_name"=>"Armand","last_name"=>"Jade","email"=>"nibh.Phasellus@purusgravida.com"),
			array("first_name"=>"Lance","last_name"=>"Heather","email"=>"vulputate@Cras.edu"),
			array("first_name"=>"Marsden","last_name"=>"Ignacia","email"=>"lacus.Aliquam.rutrum@montesnasceturridiculus.com"),
			array("first_name"=>"Len","last_name"=>"Wyoming","email"=>"dui.lectus@fermentumvel.net"),
			array("first_name"=>"Luke","last_name"=>"Lila","email"=>"Fusce.mollis@odioauctor.net"),
			array("first_name"=>"Hasad","last_name"=>"Ariel","email"=>"Sed@atrisus.edu"),
			array("first_name"=>"Michael","last_name"=>"Ciara","email"=>"Aenean.egestas.hendrerit@libero.edu"),
			array("first_name"=>"Kamal","last_name"=>"Iola","email"=>"Vivamus.nisi.Mauris@tinciduntcongueturpis.edu"),
			array("first_name"=>"Ivan","last_name"=>"Jaime","email"=>"mollis@dictummi.net"),
			array("first_name"=>"Todd","last_name"=>"India","email"=>"blandit@Nullam.co.uk"),
			array("first_name"=>"Forrest","last_name"=>"Zenia","email"=>"pede@sitamet.net"),
			array("first_name"=>"Gage","last_name"=>"Teagan","email"=>"tincidunt@metusInnec.edu"),
			array("first_name"=>"Lester","last_name"=>"Cara","email"=>"fames.ac@eumetus.com"),
			array("first_name"=>"Keaton","last_name"=>"Kyra","email"=>"Sed@risus.org"),
			array("first_name"=>"Cooper","last_name"=>"Naomi","email"=>"odio.semper.cursus@consequat.org"),
			array("first_name"=>"Sebastian","last_name"=>"Lenore","email"=>"feugiat.non@lacusQuisquepurus.org"),
			array("first_name"=>"Christopher","last_name"=>"Amber","email"=>"leo.elementum.sem@Nullam.org"),
			array("first_name"=>"Sean","last_name"=>"Mollie","email"=>"gravida.Aliquam.tincidunt@arcuSed.ca"),
			array("first_name"=>"Raja","last_name"=>"Zorita","email"=>"orci.Phasellus.dapibus@Aeneaneget.ca"),
			array("first_name"=>"Russell","last_name"=>"Evangeline","email"=>"sit@ultriciesadipiscingenim.ca"),
			array("first_name"=>"Jordan","last_name"=>"Xantha","email"=>"diam@vulputate.net"),
			array("first_name"=>"Nathaniel","last_name"=>"Chastity","email"=>"et.ultrices@liberoInteger.net"),
			array("first_name"=>"Keaton","last_name"=>"Sacha","email"=>"lorem@libero.net"),
			array("first_name"=>"Wylie","last_name"=>"Xaviera","email"=>"sem.elit.pharetra@elitdictum.ca"),
			array("first_name"=>"Tarik","last_name"=>"Lacota","email"=>"amet.dapibus@acorci.org"),
			array("first_name"=>"Cyrus","last_name"=>"Karly","email"=>"lacus.vestibulum@sem.com"),
			array("first_name"=>"Tate","last_name"=>"Tara","email"=>"augue.porttitor.interdum@risus.org"),
			array("first_name"=>"August","last_name"=>"Shannon","email"=>"bibendum.sed.est@nuncnulla.net"),
			array("first_name"=>"Chandler","last_name"=>"Pandora","email"=>"adipiscing.ligula.Aenean@necurna.co.uk"),
			array("first_name"=>"Barrett","last_name"=>"Jamalia","email"=>"ante@vulputatelacusCras.edu"),
			array("first_name"=>"Walker","last_name"=>"Mechelle","email"=>"lacus.Nulla@Nuncmaurissapien.ca"),
			array("first_name"=>"Myles","last_name"=>"Kylynn","email"=>"lorem@sagittis.co.uk"),
			array("first_name"=>"Cameron","last_name"=>"Aileen","email"=>"vitae.dolor.Donec@neque.net"),
			array("first_name"=>"Michael","last_name"=>"Nevada","email"=>"vel.lectus@Craspellentesque.net"),
			array("first_name"=>"Ian","last_name"=>"Kiayada","email"=>"eros.Proin@lectusrutrum.com"),
			array("first_name"=>"Oscar","last_name"=>"Tatyana","email"=>"et.libero@orciDonecnibh.org"),
			array("first_name"=>"Henry","last_name"=>"Rhea","email"=>"Sed@egestasAliquamfringilla.ca"),
			array("first_name"=>"Lars","last_name"=>"Daria","email"=>"velit@odio.edu"),
			array("first_name"=>"Nicholas","last_name"=>"Germane","email"=>"nibh@ametconsectetuer.edu"),
			array("first_name"=>"Herman","last_name"=>"Rhona","email"=>"auctor.Mauris.vel@odioPhasellusat.net"),
			array("first_name"=>"Silas","last_name"=>"Morgan","email"=>"dolor@Mauris.net"),
			array("first_name"=>"Abbot","last_name"=>"Lois","email"=>"tempor.est@feugiattellus.com"),
			array("first_name"=>"Ryan","last_name"=>"Tallulah","email"=>"ultrices@nec.co.uk"),
			array("first_name"=>"Lucius","last_name"=>"Halee","email"=>"diam.Sed.diam@Fuscedolor.edu"),
			array("first_name"=>"Tyrone","last_name"=>"Yoshi","email"=>"eu@tinciduntaliquam.com"),
			array("first_name"=>"Dean","last_name"=>"Leslie","email"=>"Ut.semper@consectetueradipiscingelit.co.uk"),
			array("first_name"=>"Gavin","last_name"=>"Cora","email"=>"mauris.erat@ullamcorper.com"),
			array("first_name"=>"Brody","last_name"=>"Doris","email"=>"est@sollicitudinorcisem.org"),
			array("first_name"=>"Logan","last_name"=>"Isabella","email"=>"mauris.sit@amet.net"),
			array("first_name"=>"Elton","last_name"=>"Jolene","email"=>"porttitor.tellus@faucibus.com"),
			array("first_name"=>"Denton","last_name"=>"Cathleen","email"=>"felis.adipiscing.fringilla@enimsitamet.org"),
			array("first_name"=>"Jason","last_name"=>"Ima","email"=>"nec.urna@Maecenas.net"),
			array("first_name"=>"Hall","last_name"=>"Isadora","email"=>"arcu.Vestibulum@lacusvariuset.co.uk"),
			array("first_name"=>"Ira","last_name"=>"Rose","email"=>"mauris.id.sapien@etultricesposuere.co.uk"),
			array("first_name"=>"Hector","last_name"=>"Jorden","email"=>"interdum.libero@pede.co.uk"),
			array("first_name"=>"Joshua","last_name"=>"Justina","email"=>"quis.massa.Mauris@temporarcuVestibulum.edu"),
			array("first_name"=>"Judah","last_name"=>"Camilla","email"=>"tortor.at@vulputateeu.org"),
			array("first_name"=>"Lev","last_name"=>"Mechelle","email"=>"Cras@magna.edu"),
			array("first_name"=>"Aladdin","last_name"=>"Ignacia","email"=>"odio@augueporttitorinterdum.ca"),
			array("first_name"=>"Elton","last_name"=>"Teegan","email"=>"accumsan.neque@VivamusnisiMauris.net"),
			array("first_name"=>"Jesse","last_name"=>"Zoe","email"=>"ultricies.adipiscing.enim@facilisisnon.co.uk"),
			array("first_name"=>"Chandler","last_name"=>"Ruth","email"=>"amet.metus@lobortismaurisSuspendisse.net"),
			array("first_name"=>"Elijah","last_name"=>"Flavia","email"=>"mattis.Cras.eget@Quisqueac.com"),
			array("first_name"=>"Kyle","last_name"=>"Ori","email"=>"vitae@velitCras.net"),
			array("first_name"=>"Yuli","last_name"=>"Kylie","email"=>"sit.amet@eros.edu"),
			array("first_name"=>"Silas","last_name"=>"Rosalyn","email"=>"rutrum@molestiesodales.net"),
			array("first_name"=>"Clark","last_name"=>"Alice","email"=>"dictum.placerat.augue@etmagnisdis.org"),
			array("first_name"=>"Adrian","last_name"=>"Brittany","email"=>"aptent.taciti.sociosqu@loremipsumsodales.net"),
			array("first_name"=>"Logan","last_name"=>"Amela","email"=>"mollis@quispede.co.uk"),
			array("first_name"=>"Bert","last_name"=>"Bethany","email"=>"urna.Nunc@duiCumsociis.co.uk"),
			array("first_name"=>"Judah","last_name"=>"Allegra","email"=>"Vivamus@euismodin.net"),
			array("first_name"=>"Salvador","last_name"=>"Danielle","email"=>"laoreet@ac.edu"),
			array("first_name"=>"Shad","last_name"=>"Leila","email"=>"Fusce.aliquam.enim@Integeraliquamadipiscing.edu"),
			array("first_name"=>"Jacob","last_name"=>"Wilma","email"=>"orci.in@sitamet.com"),
			array("first_name"=>"Isaac","last_name"=>"Allegra","email"=>"dolor.Fusce.feugiat@ligula.ca"),
			array("first_name"=>"Talon","last_name"=>"Pascale","email"=>"lacus.Quisque@Uttinciduntvehicula.com"),
			array("first_name"=>"Caldwell","last_name"=>"Irma","email"=>"ac@sodalespurusin.org"),
			array("first_name"=>"Blake","last_name"=>"Ora","email"=>"sit.amet@magnisdisparturient.com"),
			array("first_name"=>"Walker","last_name"=>"Quin","email"=>"mauris.Suspendisse.aliquet@arcu.net"),
			array("first_name"=>"Porter","last_name"=>"Jocelyn","email"=>"interdum@scelerisque.ca"),
			array("first_name"=>"Blake","last_name"=>"Adena","email"=>"vulputate@ut.co.uk"),
			array("first_name"=>"Garrett","last_name"=>"Maya","email"=>"porttitor.tellus.non@etmagna.com"),
			array("first_name"=>"Clarke","last_name"=>"Bo","email"=>"magna@magnaUttincidunt.com"),
			array("first_name"=>"Micah","last_name"=>"Keelie","email"=>"et.rutrum.eu@commodoatlibero.net"),
			array("first_name"=>"Boris","last_name"=>"Azalia","email"=>"Proin.vel.arcu@laoreetlectusquis.net"),
			array("first_name"=>"Elliott","last_name"=>"Yuri","email"=>"interdum@purusmauris.edu"),
			array("first_name"=>"Barry","last_name"=>"Petra","email"=>"ipsum@Cras.net"),
			array("first_name"=>"Mannix","last_name"=>"Tasha","email"=>"ac.orci@dolor.net"),
			array("first_name"=>"Tyrone","last_name"=>"Joelle","email"=>"dapibus.rutrum.justo@Duisvolutpatnunc.org"),
			array("first_name"=>"Elton","last_name"=>"Lillith","email"=>"varius.ultrices.mauris@euplacerat.ca"),
			array("first_name"=>"Jermaine","last_name"=>"Angelica","email"=>"Nunc@Nuncpulvinar.org"),
			array("first_name"=>"Price","last_name"=>"Kelly","email"=>"ornare@tempusnonlacinia.com"),
			array("first_name"=>"Melvin","last_name"=>"Fallon","email"=>"id.ante.Nunc@luctusvulputatenisi.org"),
			array("first_name"=>"Amos","last_name"=>"Britanney","email"=>"eu.elit.Nulla@consequatdolorvitae.edu"),
			array("first_name"=>"Theodore","last_name"=>"Suki","email"=>"Morbi.metus.Vivamus@at.co.uk"),
			array("first_name"=>"Cooper","last_name"=>"Athena","email"=>"Maecenas@tacitisociosqu.co.uk"),
			array("first_name"=>"Colt","last_name"=>"Amber","email"=>"Cum.sociis.natoque@Duisdignissimtempor.edu"),
			array("first_name"=>"Ethan","last_name"=>"Christine","email"=>"Fusce.dolor.quam@tempuslorem.co.uk"),
			array("first_name"=>"Fritz","last_name"=>"Zelda","email"=>"Nunc.mauris@esttemporbibendum.ca"),
			array("first_name"=>"Carter","last_name"=>"Risa","email"=>"diam@pellentesque.com"),
			array("first_name"=>"Bernard","last_name"=>"Constance","email"=>"purus.accumsan@orci.com"),
			array("first_name"=>"Jason","last_name"=>"Lydia","email"=>"eu.nulla@eros.co.uk"),
			array("first_name"=>"Joseph","last_name"=>"Ciara","email"=>"ornare@tempor.net"),
			array("first_name"=>"Giacomo","last_name"=>"Xandra","email"=>"dolor@dis.net"),
			array("first_name"=>"Henry","last_name"=>"Teagan","email"=>"felis.Nulla@tristique.org"),
			array("first_name"=>"Alec","last_name"=>"Sara","email"=>"dolor@amagna.org"),
			array("first_name"=>"Uriah","last_name"=>"Ashely","email"=>"natoque.penatibus.et@lacusUt.ca"),
			array("first_name"=>"Blaze","last_name"=>"Wynter","email"=>"elit.erat.vitae@volutpatNulla.net"),
			array("first_name"=>"Grady","last_name"=>"Virginia","email"=>"odio.sagittis.semper@atvelitCras.ca"),
			array("first_name"=>"Rashad","last_name"=>"Doris","email"=>"Fusce.aliquam.enim@Loremipsumdolor.edu"),
			array("first_name"=>"Merritt","last_name"=>"Candace","email"=>"dui.semper@Aeneanmassa.ca"),
			array("first_name"=>"Ulysses","last_name"=>"Geraldine","email"=>"non.arcu@arcu.com"),
			array("first_name"=>"Francis","last_name"=>"Madeline","email"=>"Donec@Innecorci.ca"),
			array("first_name"=>"Carlos","last_name"=>"Kessie","email"=>"non@estac.net"),
			array("first_name"=>"Hashim","last_name"=>"Lesley","email"=>"ac.metus@justo.edu"),
			array("first_name"=>"Kuame","last_name"=>"Geraldine","email"=>"lacinia@arcu.net"),
			array("first_name"=>"Hunter","last_name"=>"Irene","email"=>"ac.feugiat.non@Proineget.co.uk"),
			array("first_name"=>"Hilel","last_name"=>"Amela","email"=>"mauris.rhoncus.id@turpisegestas.net"),
			array("first_name"=>"Barrett","last_name"=>"Claire","email"=>"augue@nislQuisque.org"),
			array("first_name"=>"Colorado","last_name"=>"Vera","email"=>"elementum.at.egestas@Sedneque.org"),
			array("first_name"=>"Jonah","last_name"=>"Amaya","email"=>"Maecenas.libero.est@venenatislacus.org"),
			array("first_name"=>"Hunter","last_name"=>"Sonya","email"=>"eu@ullamcorper.co.uk"),
			array("first_name"=>"Nathan","last_name"=>"Shelley","email"=>"vitae.erat.Vivamus@id.edu"),
			array("first_name"=>"Lucian","last_name"=>"Myra","email"=>"urna.Nullam.lobortis@libero.com"),
			array("first_name"=>"Joshua","last_name"=>"Amethyst","email"=>"Nunc@utcursusluctus.co.uk"),
			array("first_name"=>"Travis","last_name"=>"Tatyana","email"=>"Sed.nunc@Donecnonjusto.com"),
			array("first_name"=>"Lyle","last_name"=>"Lee","email"=>"Suspendisse@nec.ca"),
			array("first_name"=>"Jesse","last_name"=>"Yen","email"=>"id@sollicitudin.net"),
			array("first_name"=>"Jameson","last_name"=>"TaShya","email"=>"consectetuer.adipiscing.elit@Aliquamauctor.org"),
			array("first_name"=>"Trevor","last_name"=>"Rachel","email"=>"nunc.risus.varius@necligula.co.uk"),
			array("first_name"=>"Forrest","last_name"=>"Callie","email"=>"quam@euelit.org"),
			array("first_name"=>"Brady","last_name"=>"Ainsley","email"=>"risus.Quisque@dignissimtempor.net"),
			array("first_name"=>"Charles","last_name"=>"Priscilla","email"=>"vehicula.Pellentesque@porttitorinterdumSed.co.uk"),
			array("first_name"=>"Prescott","last_name"=>"Alexa","email"=>"Aliquam@mollis.co.uk"),
			array("first_name"=>"Micah","last_name"=>"Lael","email"=>"est@ornareFuscemollis.ca"),
			array("first_name"=>"Slade","last_name"=>"Leandra","email"=>"non.lacinia.at@Curae.org"),
			array("first_name"=>"Kasper","last_name"=>"Madison","email"=>"vel.quam@semperetlacinia.net"),
			array("first_name"=>"Orson","last_name"=>"Dana","email"=>"a@Duis.net"),
			array("first_name"=>"Bert","last_name"=>"Doris","email"=>"magna.et.ipsum@posuerecubilia.edu"),
			array("first_name"=>"Stuart","last_name"=>"Phyllis","email"=>"ipsum@placerat.net"),
			array("first_name"=>"Burton","last_name"=>"Alfreda","email"=>"purus@sodalesatvelit.org"),
			array("first_name"=>"Quentin","last_name"=>"Zelenia","email"=>"ante.Maecenas.mi@Proin.com"),
			array("first_name"=>"Yasir","last_name"=>"Tatyana","email"=>"sit@purus.edu"),
			array("first_name"=>"Rajah","last_name"=>"Eve","email"=>"tristique.ac@ligula.net"),
			array("first_name"=>"Hu","last_name"=>"Demetria","email"=>"purus@urna.edu"),
			array("first_name"=>"Cameron","last_name"=>"Kylie","email"=>"risus@nislMaecenas.net"),
			array("first_name"=>"Reuben","last_name"=>"Amaya","email"=>"Duis@at.ca"),
			array("first_name"=>"Amir","last_name"=>"Chloe","email"=>"sagittis.Duis.gravida@Suspendisse.co.uk"),
			array("first_name"=>"Leo","last_name"=>"Sonya","email"=>"ut.pharetra.sed@odio.co.uk"),
			array("first_name"=>"Jerome","last_name"=>"Sierra","email"=>"Nam.ligula.elit@CuraeDonectincidunt.co.uk"),
			array("first_name"=>"Wylie","last_name"=>"Xena","email"=>"vitae@libero.org"),
			array("first_name"=>"Quamar","last_name"=>"Illiana","email"=>"nec.orci@Nuncmauris.org"),
			array("first_name"=>"Tate","last_name"=>"Jolene","email"=>"netus@loremauctorquis.net"),
			array("first_name"=>"Ivor","last_name"=>"Savannah","email"=>"Duis.risus.odio@estacmattis.com"),
			array("first_name"=>"Branden","last_name"=>"Minerva","email"=>"Cras.vulputate@Duisdignissim.co.uk"),
			array("first_name"=>"Denton","last_name"=>"Kim","email"=>"Nam@tellusjusto.net"),
			array("first_name"=>"Jin","last_name"=>"Veronica","email"=>"sed.pede.nec@feliseget.co.uk"),
			array("first_name"=>"Quamar","last_name"=>"Jorden","email"=>"eget.laoreet.posuere@urnaet.com"),
			array("first_name"=>"Hamish","last_name"=>"Lucy","email"=>"posuere@ametmassa.co.uk"),
			array("first_name"=>"Matthew","last_name"=>"Odette","email"=>"eu.neque.pellentesque@enimdiamvel.co.uk"),
			array("first_name"=>"Lewis","last_name"=>"Kaye","email"=>"rhoncus.id@Integermollis.edu"),
			array("first_name"=>"Gray","last_name"=>"Anastasia","email"=>"eget.laoreet.posuere@velnislQuisque.org"),
			array("first_name"=>"Kane","last_name"=>"Deanna","email"=>"ligula.Aenean@aptent.ca"),
			array("first_name"=>"Ashton","last_name"=>"Keiko","email"=>"In.scelerisque.scelerisque@aliquamadipiscinglacus.org"),
			array("first_name"=>"Andrew","last_name"=>"Kyra","email"=>"congue@torquent.co.uk"),
			array("first_name"=>"Isaiah","last_name"=>"Mercedes","email"=>"risus@Fuscefermentumfermentum.net"),
			array("first_name"=>"Reuben","last_name"=>"Skyler","email"=>"vel@risusQuisquelibero.edu"),
			array("first_name"=>"Cadman","last_name"=>"Nadine","email"=>"mauris@et.net"),
			array("first_name"=>"Joseph","last_name"=>"Lara","email"=>"dolor@sodalesat.ca"),
			array("first_name"=>"Jermaine","last_name"=>"Michelle","email"=>"aliquet.molestie.tellus@anteipsum.net"),
			array("first_name"=>"Calvin","last_name"=>"Blaine","email"=>"augue.porttitor@bibendum.net"),
			array("first_name"=>"Vincent","last_name"=>"Naida","email"=>"sit.amet.consectetuer@auctorvelit.net"),
			array("first_name"=>"Lane","last_name"=>"Susan","email"=>"parturient@erosProin.ca"),
			array("first_name"=>"Zachary","last_name"=>"Mara","email"=>"rhoncus@lobortisquama.co.uk"),
			array("first_name"=>"Clarke","last_name"=>"Octavia","email"=>"faucibus.orci@vitaevelitegestas.com"),
			array("first_name"=>"Plato","last_name"=>"Alexandra","email"=>"eu@quisdiamluctus.ca"),
			array("first_name"=>"Fuller","last_name"=>"Tana","email"=>"convallis@tincidunt.com"),
			array("first_name"=>"Garrett","last_name"=>"Jana","email"=>"et@feugiat.ca"),
			array("first_name"=>"Rooney","last_name"=>"Hiroko","email"=>"Ut.tincidunt@vitae.edu"),
			array("first_name"=>"Steel","last_name"=>"Anastasia","email"=>"vitae.dolor@elitpellentesquea.com"),
			array("first_name"=>"Nero","last_name"=>"Shaeleigh","email"=>"sed.turpis@euultricessit.ca"),
			array("first_name"=>"Kareem","last_name"=>"Xena","email"=>"velit.eget.laoreet@iaculis.edu"),
			array("first_name"=>"Kelly","last_name"=>"Ifeoma","email"=>"quis@arcu.net"),
			array("first_name"=>"Ezra","last_name"=>"Rama","email"=>"diam@Vivamussit.ca"),
			array("first_name"=>"Richard","last_name"=>"Zelenia","email"=>"Pellentesque@eleifendvitaeerat.com"),
			array("first_name"=>"Fulton","last_name"=>"Ruby","email"=>"sed.facilisis@Nuncacsem.net"),
			array("first_name"=>"Darius","last_name"=>"Cheyenne","email"=>"velit.Aliquam@montesnasceturridiculus.net"),
			array("first_name"=>"Maxwell","last_name"=>"Vanna","email"=>"elementum.sem.vitae@mi.ca"),
			array("first_name"=>"Chaim","last_name"=>"Demetria","email"=>"dui@ultriciesdignissimlacus.com"),
			array("first_name"=>"Lucas","last_name"=>"Idola","email"=>"non.sollicitudin.a@cursuseteros.net"),
			array("first_name"=>"Randall","last_name"=>"Lucy","email"=>"Phasellus@laciniaat.org"),
			array("first_name"=>"Arsenio","last_name"=>"Joy","email"=>"lorem.fringilla@velit.co.uk"),
			array("first_name"=>"Hu","last_name"=>"Lee","email"=>"nec.luctus@semperNam.org"),
			array("first_name"=>"Len","last_name"=>"Ann","email"=>"aliquet.odio@leo.com"),
			array("first_name"=>"Lance","last_name"=>"Ayanna","email"=>"ante.Vivamus.non@gravidanonsollicitudin.ca"),
			array("first_name"=>"Warren","last_name"=>"Sonia","email"=>"Cras.vulputate.velit@lobortisClass.com"),
			array("first_name"=>"Randall","last_name"=>"Quon","email"=>"et@eratnonummy.co.uk"),
			array("first_name"=>"Lyle","last_name"=>"Germaine","email"=>"ligula.Aenean@Curabiturmassa.org"),
			array("first_name"=>"Kuame","last_name"=>"Brittany","email"=>"aliquam.eu.accumsan@Nunccommodo.ca")
		);

		return $data;
	}
}