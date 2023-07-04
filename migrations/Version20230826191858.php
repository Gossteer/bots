<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230826191858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'first';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE message_delay (id SERIAL NOT NULL, user_id VARCHAR(255) NOT NULL, bot_id VARCHAR(255) DEFAULT NULL, database_queue_id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D3C7F70A76ED395C13DF22E ON message_delay (user_id, database_queue_id)');
        $this->addSql('COMMENT ON TABLE message_delay IS \'отложенные сообщения, которые нужно удалить при действие пользователя\'');
        $this->addSql('COMMENT ON COLUMN message_delay.user_id IS \'id пользователя мессенджера\'');
        $this->addSql('COMMENT ON COLUMN message_delay.bot_id IS \'id бота\'');
        $this->addSql('COMMENT ON COLUMN message_delay.database_queue_id IS \'id отложенной задачи\'');
        $this->addSql('COMMENT ON COLUMN message_delay.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE telegram_bot (id VARCHAR(255) NOT NULL, telegram_bot_group_id INT NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, token TEXT NOT NULL, secret_token TEXT NOT NULL, name TEXT NOT NULL, environment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CED6A3DFDC19BB1B ON telegram_bot (telegram_bot_group_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CED6A3DF5F37A13B ON telegram_bot (token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CED6A3DF9E64DD2 ON telegram_bot (secret_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CED6A3DF5E237E06 ON telegram_bot (name)');
        $this->addSql('COMMENT ON TABLE telegram_bot IS \'Список телеграмм ботов\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.is_active IS \'Признак активности бота\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.token IS \'Токен для работы с ботом\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.secret_token IS \'Секретный токен, подтверждающий бота\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.name IS \'Наименование бота в телеграмме\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.environment IS \'Окружение, в котором бот должен работать. Если null, то работает везде\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE telegram_bot_group (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AB19BEEA5E237E06 ON telegram_bot_group (name)');
        $this->addSql('COMMENT ON TABLE telegram_bot_group IS \'группа телеграмм ботов\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot_group.name IS \'название группы\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot_group.is_active IS \'Признак активности группы\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot_group.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN telegram_bot_group.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE telegram_command (id SERIAL NOT NULL, telegram_bot_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, is_needed_skip_wait_messages BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D58C8B22DC19BB1B ON telegram_command (telegram_bot_group_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D58C8B22DC19BB1B5E237E06 ON telegram_command (telegram_bot_group_id, name)');
        $this->addSql('COMMENT ON TABLE telegram_command IS \'команды для телеграмм ботов\'');
        $this->addSql('COMMENT ON COLUMN telegram_command.name IS \'название команды\'');
        $this->addSql('COMMENT ON COLUMN telegram_command.is_active IS \'признак активности команды\'');
        $this->addSql('COMMENT ON COLUMN telegram_command.is_needed_skip_wait_messages IS \'нужно ли удалять уже ожидающие отправки необязательные сообщения\'');
        $this->addSql('COMMENT ON COLUMN telegram_command.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN telegram_command.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE telegram_command_message (id SERIAL NOT NULL, telegram_command_id INT NOT NULL, telegram_message_id INT NOT NULL, sort_order INT DEFAULT 0 NOT NULL, must_wait BOOLEAN DEFAULT false NOT NULL, wait_seconds INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DBD02EAB61F1D9E0 ON telegram_command_message (telegram_command_id)');
        $this->addSql('CREATE INDEX IDX_DBD02EAB16AA253 ON telegram_command_message (telegram_message_id)');
        $this->addSql('COMMENT ON TABLE telegram_command_message IS \'связь телеграмм команды и телеграмм сообщения\'');
        $this->addSql('COMMENT ON COLUMN telegram_command_message.sort_order IS \'Порядок отправки команд\'');
        $this->addSql('COMMENT ON COLUMN telegram_command_message.must_wait IS \'обязательно отправлять после ожидания\'');
        $this->addSql('COMMENT ON COLUMN telegram_command_message.wait_seconds IS \'время ожидание в секундах\'');
        $this->addSql('COMMENT ON COLUMN telegram_command_message.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN telegram_command_message.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE telegram_message (id SERIAL NOT NULL, telegram_messenger_method_id INT NOT NULL, data JSONB NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EDFB5189F909AF66 ON telegram_message (telegram_messenger_method_id)');
        $this->addSql('COMMENT ON TABLE telegram_message IS \'сообщения для телеграмма\'');
        $this->addSql('COMMENT ON COLUMN telegram_message.data IS \'тело сообщения\'');
        $this->addSql('COMMENT ON COLUMN telegram_message.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN telegram_message.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE telegram_messenger_method (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7E9B2C15E237E06 ON telegram_messenger_method (name)');
        $this->addSql('COMMENT ON TABLE telegram_messenger_method IS \'метод api телеграмма\'');
        $this->addSql('COMMENT ON COLUMN telegram_messenger_method.name IS \'название метода\'');
        $this->addSql('COMMENT ON COLUMN telegram_messenger_method.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN telegram_messenger_method.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE user_tg (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE user_tg IS \'пользователи телеграмма\'');
        $this->addSql('COMMENT ON COLUMN user_tg.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_tg.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE user_vk (id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE user_vk IS \'Пользователи вконтакте\'');
        $this->addSql('COMMENT ON COLUMN user_vk.id IS \'Код пользователя вконтакте\'');
        $this->addSql('COMMENT ON COLUMN user_vk.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_vk.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE vk_bot (id TEXT NOT NULL, vk_bot_group_id INT NOT NULL, access_key TEXT NOT NULL, group_id INT NOT NULL, secret_token TEXT DEFAULT NULL, confirmation_token TEXT DEFAULT NULL, name TEXT NOT NULL, environment TEXT DEFAULT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EF6BCBD4D010663B ON vk_bot (vk_bot_group_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF6BCBD4EAD0F67C ON vk_bot (access_key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF6BCBD49E64DD2 ON vk_bot (secret_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF6BCBD45E237E06 ON vk_bot (name)');
        $this->addSql('COMMENT ON TABLE vk_bot IS \'Список VK ботов\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.id IS \'Идентификатор бота\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.access_key IS \'Ключ доступа в сообщество вконтакте\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.group_id IS \'Идентификатор сообщества вконтакте\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.secret_token IS \'Секретный ключ\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.confirmation_token IS \'Строка подтверждения\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.name IS \'Наименование бота\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.environment IS \'Окружение, в котором бот должен работать. Если null, то работает везде\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.is_active IS \'Признак активности бота\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vk_bot.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE vk_bot_group (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BE0BC8355E237E06 ON vk_bot_group (name)');
        $this->addSql('COMMENT ON TABLE vk_bot_group IS \'Группа VK ботов\'');
        $this->addSql('COMMENT ON COLUMN vk_bot_group.name IS \'Название группы\'');
        $this->addSql('COMMENT ON COLUMN vk_bot_group.is_active IS \'Признак активности группы\'');
        $this->addSql('COMMENT ON COLUMN vk_bot_group.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vk_bot_group.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE vk_command (id SERIAL NOT NULL, vk_bot_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, is_needed_skip_wait_messages BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_594B5610D010663B ON vk_command (vk_bot_group_id)');
        $this->addSql('COMMENT ON TABLE vk_command IS \'Команды для VK ботов\'');
        $this->addSql('COMMENT ON COLUMN vk_command.name IS \'Текст команды\'');
        $this->addSql('COMMENT ON COLUMN vk_command.is_active IS \'Признак активности команды\'');
        $this->addSql('COMMENT ON COLUMN vk_command.is_needed_skip_wait_messages IS \'Нужно ли удалять уже ожидающие отправки необязательные сообщения\'');
        $this->addSql('COMMENT ON COLUMN vk_command.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vk_command.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE vk_command_message (id SERIAL NOT NULL, vk_command_id INT NOT NULL, vk_message_id INT NOT NULL, sort_order INT DEFAULT 0 NOT NULL, must_wait BOOLEAN DEFAULT false NOT NULL, wait_seconds INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AED76BB1778804D3 ON vk_command_message (vk_command_id)');
        $this->addSql('CREATE INDEX IDX_AED76BB117137F60 ON vk_command_message (vk_message_id)');
        $this->addSql('COMMENT ON TABLE vk_command_message IS \'Связь команды и сообщения\'');
        $this->addSql('COMMENT ON COLUMN vk_command_message.sort_order IS \'Порядок отправки команд\'');
        $this->addSql('COMMENT ON COLUMN vk_command_message.must_wait IS \'обязательно отправлять после ожидания\'');
        $this->addSql('COMMENT ON COLUMN vk_command_message.wait_seconds IS \'Время ожидание в секундах\'');
        $this->addSql('COMMENT ON COLUMN vk_command_message.created_at IS \'дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vk_command_message.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE vk_message (id SERIAL NOT NULL, vk_messenger_method_id INT NOT NULL, data JSONB NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_613C8CBBDFD244C2 ON vk_message (vk_messenger_method_id)');
        $this->addSql('COMMENT ON TABLE vk_message IS \'Сообщения для вконтакте\'');
        $this->addSql('COMMENT ON COLUMN vk_message.data IS \'тело сообщения\'');
        $this->addSql('COMMENT ON COLUMN vk_message.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vk_message.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE vk_messenger_method (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AFE4CFE5E237E06 ON vk_messenger_method (name)');
        $this->addSql('COMMENT ON TABLE vk_messenger_method IS \'Метод api вконтакте\'');
        $this->addSql('COMMENT ON COLUMN vk_messenger_method.name IS \'Наименование метода\'');
        $this->addSql('COMMENT ON COLUMN vk_messenger_method.created_at IS \'Дата и время создания(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vk_messenger_method.updated_at IS \'Дата и время обновления(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE telegram_bot ADD CONSTRAINT FK_CED6A3DFDC19BB1B FOREIGN KEY (telegram_bot_group_id) REFERENCES telegram_bot_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE telegram_command ADD CONSTRAINT FK_D58C8B22DC19BB1B FOREIGN KEY (telegram_bot_group_id) REFERENCES telegram_bot_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE telegram_command_message ADD CONSTRAINT FK_DBD02EAB61F1D9E0 FOREIGN KEY (telegram_command_id) REFERENCES telegram_command (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE telegram_command_message ADD CONSTRAINT FK_DBD02EAB16AA253 FOREIGN KEY (telegram_message_id) REFERENCES telegram_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE telegram_message ADD CONSTRAINT FK_EDFB5189F909AF66 FOREIGN KEY (telegram_messenger_method_id) REFERENCES telegram_messenger_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vk_bot ADD CONSTRAINT FK_EF6BCBD4D010663B FOREIGN KEY (vk_bot_group_id) REFERENCES vk_bot_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vk_command ADD CONSTRAINT FK_594B5610D010663B FOREIGN KEY (vk_bot_group_id) REFERENCES vk_bot_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vk_command_message ADD CONSTRAINT FK_AED76BB1778804D3 FOREIGN KEY (vk_command_id) REFERENCES vk_command (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vk_command_message ADD CONSTRAINT FK_AED76BB117137F60 FOREIGN KEY (vk_message_id) REFERENCES vk_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vk_message ADD CONSTRAINT FK_613C8CBBDFD244C2 FOREIGN KEY (vk_messenger_method_id) REFERENCES vk_messenger_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE telegram_bot DROP CONSTRAINT FK_CED6A3DFDC19BB1B');
        $this->addSql('ALTER TABLE telegram_command DROP CONSTRAINT FK_D58C8B22DC19BB1B');
        $this->addSql('ALTER TABLE telegram_command_message DROP CONSTRAINT FK_DBD02EAB61F1D9E0');
        $this->addSql('ALTER TABLE telegram_command_message DROP CONSTRAINT FK_DBD02EAB16AA253');
        $this->addSql('ALTER TABLE telegram_message DROP CONSTRAINT FK_EDFB5189F909AF66');
        $this->addSql('ALTER TABLE vk_bot DROP CONSTRAINT FK_EF6BCBD4D010663B');
        $this->addSql('ALTER TABLE vk_command DROP CONSTRAINT FK_594B5610D010663B');
        $this->addSql('ALTER TABLE vk_command_message DROP CONSTRAINT FK_AED76BB1778804D3');
        $this->addSql('ALTER TABLE vk_command_message DROP CONSTRAINT FK_AED76BB117137F60');
        $this->addSql('ALTER TABLE vk_message DROP CONSTRAINT FK_613C8CBBDFD244C2');
        $this->addSql('DROP TABLE message_delay');
        $this->addSql('DROP TABLE telegram_bot');
        $this->addSql('DROP TABLE telegram_bot_group');
        $this->addSql('DROP TABLE telegram_command');
        $this->addSql('DROP TABLE telegram_command_message');
        $this->addSql('DROP TABLE telegram_message');
        $this->addSql('DROP TABLE telegram_messenger_method');
        $this->addSql('DROP TABLE user_tg');
        $this->addSql('DROP TABLE user_vk');
        $this->addSql('DROP TABLE vk_bot');
        $this->addSql('DROP TABLE vk_bot_group');
        $this->addSql('DROP TABLE vk_command');
        $this->addSql('DROP TABLE vk_command_message');
        $this->addSql('DROP TABLE vk_message');
        $this->addSql('DROP TABLE vk_messenger_method');
    }

    //{"text": "Вам нужен карьерный ассистент, если вы:\n\n▪Активно ищете работу.\n\n▪Работаете, но хотите получать больше.\n\n▪Хотите узнать, сколько можно зарабатывать с вашим опытом и образованием.", "chat_id": "{user_id}", "parse_mode": null, "reply_markup": "{\"inline_keyboard\":[[{\"text\":\"🔸 Как подключить?\",\"callback_data\":\"career_how_connect\"}]]}", "reply_to_message_id": 0, "disable_notification": false, "disable_web_page_preview": false}
    //{"message": "text", "user_id": "{user_id}", "random_id": 0}
}
