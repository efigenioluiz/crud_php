<?php

    session_start();

    include_once '../../global.php';

    class CAluno {

        public static function index() {
            echo "<script>window.location='../../recursos/view/aluno.php'</script>";
        }

        public static function rota() {

            $dados = explode("/", $_POST['acao']);

            if(strcmp($dados[0], "cadastrar") == 0) {
                self::cadastrar();
            }
            else if(strcmp($dados[0], "alterar") == 0) {
                self::alterar($dados[1]);
            }
            else if(strcmp($dados[0], "remover") == 0) {
                self::remover($dados[1]);
            }
            else if(strcmp($dados[0], "confirmar") == 0) {
                self::confirmar($dados[1]);
            }
            else if(strcmp($dados[0], "finalizar") == 0) {
                self::finalizar($dados[1]);
            }
        }

        public static function cadastrar() {
            echo "<script>window.location='../../recursos/view/alunoCadastrar.php'</script>";
        }

        public static function alterar($id) {

            $aluno = Aluno::findAluno($id);

            if(empty($aluno)) {
                $_SESSION['MSGBOX_TITULO'] = "OPERAÇÃO INVÁLIDA!";
                $_SESSION['MSGBOX_MSG'] = "O ID informado para o aluno não existe!";
                $_SESSION['MSGBOX_LINK'] = "aluno.php";
                $_SESSION['MSGBOX_CLASSE'] = "alert alert-danger";

                echo "<script>window.location='../../recursos/view/messagebox.php'</script>";
            }
            else {

                $url = "../view/alunoAlterar.php?id=$aluno->id";
                $url .= "&nome=$aluno->nome";
                $url .= "&fk_curso=$aluno->fk_curso";
                $url .= "&fk_turma=$aluno->fk_turma";

                echo "<script>window.location='".$url."'</script>";
            }
        }

        public static function remover($id) {

            $aluno = Aluno::findAluno($id);

            if(empty($aluno)) {
                $_SESSION['MSGBOX_TITULO'] = "OPERAÇÃO INVÁLIDA!";
                $_SESSION['MSGBOX_MSG'] = "O ID informado para o aluno não existe!";
                $_SESSION['MSGBOX_LINK'] = "aluno.php";
                $_SESSION['MSGBOX_CLASSE'] = "alert alert-danger";

                echo "<script>window.location='../../recursos/view/messagebox.php'</script>";
            }
            else {

                $url = "../view/alunoRemover.php?id=$aluno->id";
                $url .= "&nome=$aluno->nome";
                echo "<script>window.location='".$url."'</script>";
            }
        }

        public static function confirmar($id) {

            if($_POST['nome'] != "" && $_POST['fk_curso'] != "" && $_POST['fk_turma'] != "") {

                $dados_aluno = array("nome" => mb_strtoupper($_POST['nome'], 'UTF-8'),
                    "fk_curso" => $_POST['fk_curso'],
                    "fk_turma" => $_POST['fk_turma']
                );
                
                // Inserir
                if($id == 0) {
                    Aluno::addAluno($dados_aluno);
                    $_SESSION['MSGBOX_MSG'] = "O aluno foi cadastrado no sistema!";
                }
                // Alterar
                else {
                    Aluno::upAluno($id, $dados_aluno);
                    $_SESSION['MSGBOX_MSG'] = "Os dados do aluno foram alterados no sistema!";
                }

                $_SESSION['MSGBOX_TITULO'] = "OPERAÇÃO REALIZADA COM SUCESSO!";
                $_SESSION['MSGBOX_LINK'] = "aluno.php";
                $_SESSION['MSGBOX_CLASSE'] = "alert alert-success";

                echo "<script>window.location='../../recursos/view/messagebox.php'</script>";
            }
            else {
                $_SESSION['MSGBOX_TITULO'] = "OPERAÇÃO INVÁLIDA!";
                $_SESSION['MSGBOX_MSG'] = "Todos os campos devem ser preenchidos!";
                $_SESSION['MSGBOX_CLASSE'] = "alert alert-warning";

                if($id == 0) { $_SESSION['MSGBOX_LINK'] = "alunoCadastrar.php"; }
                else { $_SESSION['MSGBOX_LINK'] = "alunoAlterar.php"; }
            }
        }

        public static function finalizar($id) {

            Aluno::delAluno($id);

            $_SESSION['MSGBOX_TITULO'] = "OPERAÇÃO REALIZADA COM SUCESSO!";
            $_SESSION['MSGBOX_MSG'] = "O aluno foi removido do sistema!";
            $_SESSION['MSGBOX_LINK'] = "aluno.php";
            $_SESSION['MSGBOX_CLASSE'] = "alert alert-success";

            echo "<script>window.location='../../recursos/view/messagebox.php'</script>";
        }

        public static function loadTabelaAlunos() {

			$alunos = Aluno::getAlunos();

            while($objAluno = $alunos->fetchObject()) {
                
                echo "<tr>";
                echo "<td>".$objAluno->nome."</td>";
                
                    $ObjCurso= CCurso::montaCurso($objAluno->fk_curso);
                    $ObjTurma= CTurma::montaTurma($objAluno->fk_turma);

                    echo "<td>".$ObjCurso->nome."</td>";
                    echo "<td>".$ObjTurma->nome.' '.$ObjTurma->ano."</td>";


					echo "<td>";
						echo "<button type='submit' name='acao' value='alterar/".$objAluno->id."'>";
							echo "<img src='../img/edit.svg'>";
						echo "</button>";
						echo "&nbsp";
						echo "<button type='submit' name='acao' value='remover/".$objAluno->id."'>";
							echo "<img src='../img/delete.svg'>";
						echo "</button>";
					echo "</td>";
				echo "</tr>";
    		}
    	}
    }
