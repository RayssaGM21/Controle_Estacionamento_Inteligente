# Sistema de Controle Inteligente de Estacionamento

Um sistema robusto e escalável para gerenciamento de estacionamentos, desenvolvido com arquitetura bem definida, padrões de design consolidados e princípios SOLID de engenharia de software.

## Visão Geral

O **Sistema de Controle Inteligente de Estacionamento** é uma aplicação web desenvolvida em PHP que permite o gerenciamento eficiente de veículos em estacionamentos. O sistema calcula tarifas dinâmicas conforme o tipo de veículo (carro, motocicleta, caminhão), registra sessões de estacionamento e gera relatórios detalhados sobre faturamento e ocupação.

### Principais Funcionalidades

- Registro de Sessões de Estacionamento: Cadastro de entrada e saída de veículos com cálculo automático de tarifa
- Cálculo de Tarifas Dinâmicas: Diferentes valores por tipo de veículo e duração
- Gestão de Veículos: Suporte para carros, motocicletas e caminhões
- Relatórios Analíticos: Dashboard com estatísticas de faturamento e ocupação
- Validação de Dados: Validações robustas para garantir integridade dos dados
- Persistência em Banco de Dados: SQLite para armazenamento seguro

---

## Arquitetura e Organização

O projeto segue uma arquitetura em camadas bem definida, promovendo separação de responsabilidades e facilitando manutenção e testes:

```
src/
├── Application/                          # Camada de Aplicação
│   ├── Controller/
│   │   ├── BaseController.php           # Controladora base abstrata
│   │   ├── HomeController.php           # Página inicial
│   │   ├── ParkingSessionController.php # Gerenciamento de sessões
│   │   ├── ReportController.php         # Relatórios e dashboards
│   │   └── ErrorController.php          # Tratamento de erros
│   ├── Service/
│   │   └── ReportService.php            # Serviço de geração de relatórios
│   └── View/                            # Templates de visualização
│       ├── layout.php                   # Layout principal
│       ├── home/
│       │   └── index.php
│       ├── parking_session/
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── list.php
│       ├── report/
│       │   ├── dashboard.php
│       │   └── detailed.php
│       └── error/
│           ├── 404.php
│           └── 500.php
├── Domain/                              # Camada de Domínio (Regras de Negócio)
│   ├── ParkingSession.php              # Entidade de domínio
│   ├── ParkingSessionRepository.php    # Interface de repositório
│   ├── ParkingSessionValidator.php     # Validação de sessões
│   ├── TariffRules.php                 # Interface de estratégia de tarifa
│   ├── Services/
│   │   └── TariffCalculator.php        # Serviço de cálculo de tarifas
│   └── Rules/
│       ├── CarRules.php                # Regra de tarifa para carros
│       ├── MotorcycleRules.php         # Regra de tarifa para motocicletas
│       └── TruckRules.php              # Regra de tarifa para caminhões
└── Infrastructure/                      # Camada de Infraestrutura
    ├── Database/
    │   └── Database.php                # Gerenciador de conexão SQLite
    └── Repository/
        └── SqliteParkingSessionRepository.php # Implementação de persistência
```

### Estrutura de Camadas

| Camada | Responsabilidade | Exemplos |
|--------|-----------------|----------|
| **Application** | Requisições HTTP, Respostas, Orquestração | Controllers, Services, Views |
| **Domain** | Regras de negócio, Entidades | ParkingSession, TariffRules, TariffCalculator |
| **Infrastructure** | Acesso a dados, Banco de dados | Database, Repository |

---

## Princípios SOLID Implementados

### 1. **S - Single Responsibility Principle (SRP)**

Cada classe possui uma única responsabilidade bem definida:

- **`ParkingSession`**: Representa apenas uma sessão de estacionamento
- **`TariffCalculator`**: Responsável exclusivamente pelo cálculo de tarifas
- **`ReportService`**: Gera relatórios a partir de dados do repositório
- **`ParkingSessionRepository`**: Acessa e persiste dados de sessões
- **`BaseController`**: Renderiza views e retorna respostas

**Benefício**: Facilita testes unitários, manutenção e reutilização de código.

### 2. **O - Open/Closed Principle (OCP)**

O sistema está aberto para extensão mas fechado para modificação:

- **Nova regra de tarifa?** Implemente a interface `TariffRules` sem modificar código existente
- **Novo tipo de veículo?** Adicione uma nova classe de regras e registre no `TariffCalculator`
- O `TariffCalculator` não precisa ser modificado para suportar novos tipos

```php
// Extensível sem modificar código existente
$rules = [
    'car'       => new CarRules(),
    'motorcycle' => new MotorcycleRules(),
    'truck'     => new TruckRules(),
    // Adicione novos tipos conforme necessário
];
```

**Benefício**: Novas funcionalidades sem risco de quebrar código existente.

### 3. **L - Liskov Substitution Principle (LSP)**

Toda implementação de `TariffRules` pode ser usada indiferentemente:

```php
interface TariffRules {
    public function calculate(int $hours): float;
}

class CarRules implements TariffRules { /* ... */ }
class MotorcycleRules implements TariffRules { /* ... */ }
class TruckRules implements TariffRules { /* ... */ }
```

Qualquer implementação pode substituir a outra sem quebrar o código.

**Benefício**: Polimorfismo seguro e previsível.

### 4. **I - Interface Segregation Principle (ISP)**

Interfaces específicas e pequenas em vez de grandes e genéricas:

- **`TariffRules`**: Interface mínima com apenas `calculate()`
- **`ParkingSessionRepository`**: Interface contendo apenas operações de persistência
- Controllers não dependem de métodos desnecessários

**Benefício**: Classes dependem apenas do que realmente usam.

### 5. **D - Dependency Inversion Principle (DIP)**

Módulos de alto nível não dependem de módulos de baixo nível; ambos dependem de abstrações:

- `TariffCalculator` depende de `TariffRules` (interface), não de implementações concretas
- `ReportService` depende de `ParkingSessionRepository` (interface)
- Controllers recebem dependências via injeção

**Benefício**: Facilita testes, manutenção e troca de implementações.

---

## Design Patterns Utilizados

### 1. **Strategy Pattern**

**Localização**: `TariffCalculator` com `TariffRules`

**Descrição**: Define uma família de algoritmos (cálculo de tarifa), encapsula cada um e os torna intercambiáveis.

```php
// TariffCalculator usa diferentes estratégias de cálculo
private array $rules = [];

public function calculate(string $vehicleType, int $parkedHours): float
{
    return $this->rules[$vehicleType]->calculate($parkedHours);
}
```

**Vantagem**: Permite trocar algoritmo em tempo de execução sem alterar código cliente.

### 2. **Repository Pattern**

**Localização**: `ParkingSessionRepository` e `SqliteParkingSessionRepository`

**Descrição**: Abstrai o acesso a dados, permitindo trocar de banco sem afetar o domínio.

```php
interface ParkingSessionRepository {
    public function save(ParkingSession $session): void;
    public function getAll(): array;
    public function findById(int $id): ?ParkingSession;
}
```

**Vantagem**: Aplicação não conhece detalhes de persistência (SQLite, MySQL, etc).

### 3. **Template Method Pattern**

**Localização**: `BaseController`

**Descrição**: Define a estrutura de um algoritmo, delegando algumas etapas para subclasses.

```php
abstract class BaseController {
    protected function render(string $viewName, array $data = []): void
    {
        // Template: extrair dados → renderizar view → incluir layout
        extract($data, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/../View/' . $viewName . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../View/layout.php';
    }
}
```

**Vantagem**: Evita duplicação de código em múltiplos controllers.

### 4. **Factory Pattern**

**Localização**: Inicialização de regras de tarifa

**Descrição**: Cria objetos sem especificar suas classes concretas exatas.

```php
$rules = [
    'car'       => new CarRules(),
    'motorcycle' => new MotorcycleRules(),
    'truck'     => new TruckRules(),
];

$calculator = new TariffCalculator($rules);
```

**Vantagem**: Simplifica criação de objetos complexos.

### 5. **Service Layer Pattern**

**Localização**: `ReportService`, `TariffCalculator`

**Descrição**: Encapsula lógica de negócio em camada separada.

```php
final class ReportService
{
    private ParkingSessionRepository $repository;

    public function generateReportByType(): array { /* ... */ }
    public function getDashboardStats(): array { /* ... */ }
}
```

**Vantagem**: Centraliza regras de negócio, facilita reutilização e testes.

---

## Tecnologias e Dependências

| Tecnologia | Versão | Propósito |
|-----------|--------|----------|
| **PHP** | 7.4+ | Linguagem principal |
| **SQLite** | Nativo | Banco de dados |
| **Composer** | PSR-4 | Autoloading de classes |
| **HTML5/CSS3** | - | Interface web |

### Requisitos do Sistema

- PHP 7.4 ou superior
- Extensão SQLite ativada
- Composer instalado (opcional, pré-configurado)
- Servidor web (Apache, Nginx ou PHP built-in)

---

## Como Usar

### 1. Instalação

```bash
# Clone o repositório
git clone https://github.com/RayssaGM21/Controle_Estacionamento_Inteligente.git

# Navegue até o diretório
cd Controle_Estacionamento_Inteligente

# Instale dependências (se necessário)
composer install
```

### 2. Configuração do Banco de Dados

```bash
# Execute as migrações
php storage/migrate.php

# Verifique as tabelas criadas
php storage/check_tables.php
```

### 3. Iniciar o Servidor

```bash
# Usando PHP built-in server
composer serve

# Ou manualmente
php -S localhost:8000 -t public
```

Acesse em: `http://localhost:8000`

---

## Estrutura de Dados

### Tabela: `parking_sessions`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INTEGER | Identificador único (PK) |
| `plate` | TEXT | Placa do veículo |
| `vehicle_type` | TEXT | Tipo (car, motorcycle, truck) |
| `parked_hours` | INTEGER | Horas estacionadas |
| `final_tariff` | REAL | Valor da tarifa |
| `entry_time` | DATETIME | Data/hora de entrada |

---

## Funcionalidades por Módulo

### Application/Controller
- **HomeController**: Página inicial e navegação
- **ParkingSessionController**: Criar, editar, listar sessões
- **ReportController**: Gerar e visualizar relatórios
- **ErrorController**: Tratamento de erros

### Domain
- **ParkingSession**: Entidade de domínio
- **TariffRules**: Interface de estratégia de cálculo
- **CarRules/MotorcycleRules/TruckRules**: Implementações específicas
- **TariffCalculator**: Orquestrador de cálculos

### Infrastructure
- **Database**: Gerenciador de conexão SQLite
- **SqliteParkingSessionRepository**: Implementação de persistência

---

## Destaques Técnicos

- Type Hints: Uso extensivo de types para segurança
- Documentação: Docblocks completos em todas as classes
- Separação de Responsabilidades: Arquitetura em camadas
- Extensibilidade: Fácil adicionar novos tipos de veículos
- Validação: Dados validados antes de persistir
- DRY (Don't Repeat Yourself): Código reutilizável

---

## Participantes do Projeto

| RA | Nome |
|-------|------|
| 2001130 | Rayssa Gomides Marconato |
| 1998912 | Matheus Gomes	 |

