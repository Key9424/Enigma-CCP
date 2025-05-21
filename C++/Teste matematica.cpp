#include <iostream>
#include <fstream>  // Para manipular arquivos
#include <cstdlib>  // Para limpar a tela com system("cls")
#include <ctime>    // Para números aleatórios
#include <sstream>  // Para converter números em string
using namespace std;

// Função para converter um número em string
string intToString(int num) {
    stringstream ss;
    ss << num;
    return ss.str();
}

// Função para codificar um texto usando deslocamento aleatório
string encodeText(string text) {
    string encoded = "";

    for (size_t i = 0; i < text.size(); i++) {
        char c = text[i];

        if (isalpha(c)) {  // Verifica se é uma letra
            char upperC = toupper(c);  // Converte para maiúscula
            int shift = rand() % 9 + 1;  // Gera um deslocamento aleatório
            
            // Aplica a cifra de César personalizada
            char newChar = ((upperC - 'A' + shift) % 26) + 'A';
            
            // Adiciona o número antes da letra codificada
            encoded += intToString(shift) + newChar;
        } else {
            encoded += c;  // Mantém caracteres que não são letras
        }
    }

    return encoded;
}

// Função para decodificar um texto
string decodeText(string codedText) {
    string decoded = "";
    size_t i = 0;

    while (i < codedText.size()) {
        if (isdigit(codedText[i])) {
            int shift = codedText[i] - '0';  // Converte char para número
            char encodedChar = codedText[i + 1];  // Pega a letra codificada
            
            // Reverte o deslocamento aplicado na codificação
            char decodedChar = ((encodedChar - 'A' - shift + 26) % 26) + 'A';

            decoded += decodedChar;
            i += 2;
        } else {
            decoded += codedText[i];  // Mantém espaços e caracteres especiais
            i++;
        }
    }

    return decoded;
}

// Função para salvar texto codificado no bloco de notas
void saveToNotes(string text) {
    ofstream file("bloco_de_notas.txt", ios::app);
    if (file.is_open()) {
        file << text << endl;
        file.close();
        cout << "Frase salva no bloco de notas!\n";
    } else {
        cout << "Erro ao salvar a frase.\n";
    }
}

// Função para visualizar o bloco de notas
void viewNotes() {
    ifstream file("bloco_de_notas.txt");
    string line;

    if (file.is_open()) {
        cout << "\n--- Bloco de Notas ---\n";
        while (getline(file, line)) {
            cout << line << endl;
        }
        file.close();
    } else {
        cout << "Bloco de notas vazio ou erro ao abrir o arquivo.\n";
    }
}

// Função para excluir todo o conteúdo do bloco de notas
void clearNotes() {
    char confirm;
    cout << "Tem certeza que deseja apagar todo o conteudo do bloco de notas? (S/N): ";
    cin >> confirm;
    cin.ignore();

    if (confirm == 'S' || confirm == 's') {
        ofstream file("bloco_de_notas.txt", ios::trunc);  // Apaga o conteúdo do arquivo
        file.close();
        cout << "Bloco de notas foi apagado com sucesso!\n";
    } else {
        cout << "Operacao cancelada.\n";
    }
}

// Função para excluir uma frase específica do bloco de notas
void deleteSpecificNote() {
    ifstream file("bloco_de_notas.txt");
    if (!file.is_open()) {
        cout << "Erro ao abrir o bloco de notas ou arquivo vazio.\n";
        return;
    }

    string line, phraseToDelete;
    cout << "Digite a frase codificada que deseja apagar: ";
    cin.ignore();
    getline(cin, phraseToDelete);

    // Criando um arquivo temporário para salvar as frases restantes
    ofstream tempFile("temp.txt");

    bool found = false;
    while (getline(file, line)) {
        if (line != phraseToDelete) {
            tempFile << line << endl;
        } else {
            found = true;
        }
    }

    file.close();
    tempFile.close();

    if (found) {
        remove("bloco_de_notas.txt");
        rename("temp.txt", "bloco_de_notas.txt");
        cout << "Frase apagada com sucesso!\n";
    } else {
        remove("temp.txt");
        cout << "Frase nao encontrada no bloco de notas.\n";
    }
}

int main() {
    srand(time(0));
    int option;
    string text;

    do {
        
        cout << "\n*** MENU ***\n";
        cout << "1 - Codificar Texto\n";
        cout << "2 - Decodificar Texto\n";
        cout << "3 - Salvar frase codificada no bloco de notas\n";
        cout << "4 - Ver bloco de notas\n";
        cout << "5 - Apagar uma frase especifica do bloco de notas\n";
        cout << "6 - Apagar todo o conteudo do bloco de notas\n";
        cout << "0 - Sair\n";
        cout << "Escolha uma opcao: ";
        cin >> option;
        cin.ignore();
		system("cls"); // Limpa a tela antes de mostrar o menu
        switch (option) {
            case 0:
                cout << "Saindo do programa...\n";
                return 0;
            case 1:
                cout << "Digite o texto para codificar: ";
                getline(cin, text);
                text = encodeText(text);
                cout << "Texto Codificado: " << text << endl;
                break;
            case 2:
                cout << "Digite o texto codificado para decodificar: ";
                getline(cin, text);
                cout << "Texto Decodificado: " << decodeText(text) << endl;
                break;
            case 3:
                cout << "Digite a frase para codificar e salvar no bloco de notas: ";
                getline(cin, text);
                text = encodeText(text);
                saveToNotes(text);
                break;
            case 4:
                viewNotes();
                break;
            case 5:
                deleteSpecificNote();
                break;
            case 6:
                clearNotes();
                break;
            default:
                cout << "Opcao invalida! Escolha novamente.\n";
        }
    } while (option != 0);

    return 0;
}

