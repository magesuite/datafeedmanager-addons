<?php
declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Model\ResourceModel;

class CategoryCopier
{
    protected \Magento\Framework\App\ResourceConnection $resourceConnection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function copy(int $sourceId, int $targetId): void
    {
        $connection = $this->resourceConnection->getConnection();
        $query = sprintf(
            'UPDATE %1$s AS target
                    INNER JOIN %1$s AS source ON source.id = %2$d
                    SET target.categories = source.categories
                    WHERE target.id = %3$d;
            ',
            $connection->getTableName('datafeedmanager_feeds'),
            $sourceId,
            $targetId
        );
        $connection->query($query);
    }
}
