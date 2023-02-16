@extends('crudbooster::admin_template')
@section('content')
    @include('crudbooster::statistic_builder.index')
    <div class="modal fade" id="tos-modal" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header alert-info" style="text-center">
					<h4 class="modal-title"><b>Terms of Service</b></h4>
				</div>
					<div class="modal-body">
						<h4>
						Welcome to Digits!
						</h4>
						<p>
						Thanks for using our products and services (“Services”). The Services are provided by Digits Trading Corporation.<br><br>
						By using our Services, you are agreeing to these terms. Please read them carefully.<br><br>
						Our services are very diverse, so sometimes additional terms or product requirements may apply.
						Additional terms will be available with the relevant services and documents, and those additional terms become part of your agreement with us if you use these services.<br><br>
						</p>

						<h4>
						Using our Services
						</h4>
						<p>
						You must follow any policies made available to you within the Services. Don’t misuse our Services. For example, don’t interfere with our Services or try to access them using a method other than the interface and the instructions that we provide. 
						You may use our services only as permitted by law, including applicable export and re-export control laws and regulations. 
						We may suspend or stop providing our services to you if you do not comply with our terms or policies or if we are investigating suspected misconduct.<br><br>
						Using our Services does not give you ownership of any intellectual property rights in our services or the content you access. 
						You may not use content from our services unless you obtain permission from its owner or are otherwise permitted by Digits’ policies and procedures.<br><br>
						In connection with your use of the services, we may send you service announcements, administrative messages, and other information. 
						Please read and understand them carefully, as these are policies and procedures set by the company.<br><br>
						</p>

						<h4>
						Your Account
						</h4>
						<p>
						To protect your personal account, keep your password confidential. You are responsible for the activity that happens on or through your account. 
						Please ensure that all default passwords provided by the company are changed once you receive your account information.<br><br>
						</p>

						<h4>
						Modifying and Terminating our Services
						</h4>
						<p>
						We are constantly changing and improving our Services. We may add or remove functionalities or features, and we may suspend or stop a Service altogether. 
						If we discontinue a Service, where reasonably possible, we will give you reasonable advance notice and a chance to get information out of that Service.<br><br>
						</p>

						<h4>
						Proper uses of our Services
						</h4>
						<p>
						Our services must be used properly and in accordance to Digits’ policies and procedures.<br><br>
						Please refrain from performing any test transactions using our official servers. 
						A proper test server will be provided as an avenue for users to perform tests, practice transactions, and teach colleagues on the usage of our services.<br><br>
						</p>

						<h4>
						Business uses of our Services
						</h4>
						<p>
						If you are using our services on behalf of a business, that business accepts these terms. 
						It will hold harmless and indemnify Digits and its affiliates, officers, agents, and employees from any claim, 
						suit or action arising from or related to the use of the services or violation of these terms, including any liability or expense arising from claims, 
						losses, damages, suits, judgments, litigation costs and attorneys’ fees.<br><br>
						</p>

						<h4>
						About these Terms
						</h4>
						<p>
						We may modify these terms or any additional terms that apply to a service to, for example, reflect changes to the policies, procedures, or changes to our services. 
						You should look at the terms regularly.<br><br>
						If there is a conflict between these terms and the additional terms, the additional terms will control for that conflict.<br><br>
						If you do not comply with these terms, and we don’t take action right away, this doesn’t mean that we are giving up any rights that we may have (such as taking action in the future).<br><br>
						If it turns out that a particular term is not enforceable, this will not affect any other terms.<br><br>
						</p>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="close_tos" data-dismiss="modal" ><i class="fa fa-thumbs-up"></i> Agree</button>
					</div>
				</div>
			</div>
		</div>
@endsection
@push('bottom')
    <script type="text/javascript">
        $(window).on('load',function(){
            @if (Session::get('tos_acceptance') == 0)
                $('#tos-modal').modal('show');
            @endif     
        });

        $("#close_tos").on('click',function(){
            <?=Session::put("tos_acceptance", 1);?>
        });
    </script>
@endpush 