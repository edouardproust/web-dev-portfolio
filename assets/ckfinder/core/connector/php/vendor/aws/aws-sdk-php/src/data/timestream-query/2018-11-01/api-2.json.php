<?php
// This file was auto-generated from sdk-root/src/data/timestream-query/2018-11-01/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2018-11-01', 'endpointPrefix' => 'query.timestream', 'jsonVersion' => '1.0', 'protocol' => 'json', 'serviceAbbreviation' => 'Timestream Query', 'serviceFullName' => 'Amazon Timestream Query', 'serviceId' => 'Timestream Query', 'signatureVersion' => 'v4', 'signingName' => 'timestream', 'targetPrefix' => 'Timestream_20181101', 'uid' => 'timestream-query-2018-11-01', ], 'operations' => [ 'CancelQuery' => [ 'name' => 'CancelQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CancelQueryRequest', ], 'output' => [ 'shape' => 'CancelQueryResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], 'idempotent' => true, ], 'CreateScheduledQuery' => [ 'name' => 'CreateScheduledQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateScheduledQueryRequest', ], 'output' => [ 'shape' => 'CreateScheduledQueryResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ServiceQuotaExceededException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], 'idempotent' => true, ], 'DeleteScheduledQuery' => [ 'name' => 'DeleteScheduledQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteScheduledQueryRequest', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], 'idempotent' => true, ], 'DescribeEndpoints' => [ 'name' => 'DescribeEndpoints', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeEndpointsRequest', ], 'output' => [ 'shape' => 'DescribeEndpointsResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'ThrottlingException', ], ], 'endpointoperation' => true, ], 'DescribeScheduledQuery' => [ 'name' => 'DescribeScheduledQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeScheduledQueryRequest', ], 'output' => [ 'shape' => 'DescribeScheduledQueryResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], ], 'ExecuteScheduledQuery' => [ 'name' => 'ExecuteScheduledQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ExecuteScheduledQueryRequest', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], 'idempotent' => true, ], 'ListScheduledQueries' => [ 'name' => 'ListScheduledQueries', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListScheduledQueriesRequest', ], 'output' => [ 'shape' => 'ListScheduledQueriesResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], ], 'ListTagsForResource' => [ 'name' => 'ListTagsForResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListTagsForResourceRequest', ], 'output' => [ 'shape' => 'ListTagsForResourceResponse', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], ], 'PrepareQuery' => [ 'name' => 'PrepareQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'PrepareQueryRequest', ], 'output' => [ 'shape' => 'PrepareQueryResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], 'idempotent' => true, ], 'Query' => [ 'name' => 'Query', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'QueryRequest', ], 'output' => [ 'shape' => 'QueryResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'QueryExecutionException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], 'idempotent' => true, ], 'TagResource' => [ 'name' => 'TagResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'TagResourceRequest', ], 'output' => [ 'shape' => 'TagResourceResponse', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ServiceQuotaExceededException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], ], 'UntagResource' => [ 'name' => 'UntagResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'UntagResourceRequest', ], 'output' => [ 'shape' => 'UntagResourceResponse', ], 'errors' => [ [ 'shape' => 'ValidationException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], ], 'UpdateScheduledQuery' => [ 'name' => 'UpdateScheduledQuery', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'UpdateScheduledQueryRequest', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'InvalidEndpointException', ], ], 'endpointdiscovery' => [ 'required' => true, ], ], ], 'shapes' => [ 'AccessDeniedException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ServiceErrorMessage', ], ], 'exception' => true, 'synthetic' => true, ], 'AmazonResourceName' => [ 'type' => 'string', 'max' => 2048, 'min' => 1, ], 'CancelQueryRequest' => [ 'type' => 'structure', 'required' => [ 'QueryId', ], 'members' => [ 'QueryId' => [ 'shape' => 'QueryId', ], ], ], 'CancelQueryResponse' => [ 'type' => 'structure', 'members' => [ 'CancellationMessage' => [ 'shape' => 'String', ], ], ], 'ClientRequestToken' => [ 'type' => 'string', 'max' => 128, 'min' => 32, 'sensitive' => true, ], 'ClientToken' => [ 'type' => 'string', 'max' => 128, 'min' => 32, 'sensitive' => true, ], 'ColumnInfo' => [ 'type' => 'structure', 'required' => [ 'Type', ], 'members' => [ 'Name' => [ 'shape' => 'String', ], 'Type' => [ 'shape' => 'Type', ], ], ], 'ColumnInfoList' => [ 'type' => 'list', 'member' => [ 'shape' => 'ColumnInfo', ], ], 'ConflictException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], 'CreateScheduledQueryRequest' => [ 'type' => 'structure', 'required' => [ 'Name', 'QueryString', 'ScheduleConfiguration', 'NotificationConfiguration', 'ScheduledQueryExecutionRoleArn', 'ErrorReportConfiguration', ], 'members' => [ 'Name' => [ 'shape' => 'ScheduledQueryName', ], 'QueryString' => [ 'shape' => 'QueryString', ], 'ScheduleConfiguration' => [ 'shape' => 'ScheduleConfiguration', ], 'NotificationConfiguration' => [ 'shape' => 'NotificationConfiguration', ], 'TargetConfiguration' => [ 'shape' => 'TargetConfiguration', ], 'ClientToken' => [ 'shape' => 'ClientToken', 'idempotencyToken' => true, ], 'ScheduledQueryExecutionRoleArn' => [ 'shape' => 'AmazonResourceName', ], 'Tags' => [ 'shape' => 'TagList', ], 'KmsKeyId' => [ 'shape' => 'StringValue2048', ], 'ErrorReportConfiguration' => [ 'shape' => 'ErrorReportConfiguration', ], ], ], 'CreateScheduledQueryResponse' => [ 'type' => 'structure', 'required' => [ 'Arn', ], 'members' => [ 'Arn' => [ 'shape' => 'AmazonResourceName', ], ], ], 'Datum' => [ 'type' => 'structure', 'members' => [ 'ScalarValue' => [ 'shape' => 'ScalarValue', ], 'TimeSeriesValue' => [ 'shape' => 'TimeSeriesDataPointList', ], 'ArrayValue' => [ 'shape' => 'DatumList', ], 'RowValue' => [ 'shape' => 'Row', ], 'NullValue' => [ 'shape' => 'NullableBoolean', ], ], ], 'DatumList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Datum', ], ], 'DeleteScheduledQueryRequest' => [ 'type' => 'structure', 'required' => [ 'ScheduledQueryArn', ], 'members' => [ 'ScheduledQueryArn' => [ 'shape' => 'AmazonResourceName', ], ], ], 'DescribeEndpointsRequest' => [ 'type' => 'structure', 'members' => [], ], 'DescribeEndpointsResponse' => [ 'type' => 'structure', 'required' => [ 'Endpoints', ], 'members' => [ 'Endpoints' => [ 'shape' => 'Endpoints', ], ], ], 'DescribeScheduledQueryRequest' => [ 'type' => 'structure', 'required' => [ 'ScheduledQueryArn', ], 'members' => [ 'ScheduledQueryArn' => [ 'shape' => 'AmazonResourceName', ], ], ], 'DescribeScheduledQueryResponse' => [ 'type' => 'structure', 'required' => [ 'ScheduledQuery', ], 'members' => [ 'ScheduledQuery' => [ 'shape' => 'ScheduledQueryDescription', ], ], ], 'DimensionMapping' => [ 'type' => 'structure', 'required' => [ 'Name', 'DimensionValueType', ], 'members' => [ 'Name' => [ 'shape' => 'SchemaName', ], 'DimensionValueType' => [ 'shape' => 'DimensionValueType', ], ], ], 'DimensionMappingList' => [ 'type' => 'list', 'member' => [ 'shape' => 'DimensionMapping', ], ], 'DimensionValueType' => [ 'type' => 'string', 'enum' => [ 'VARCHAR', ], ], 'Double' => [ 'type' => 'double', ], 'Endpoint' => [ 'type' => 'structure', 'required' => [ 'Address', 'CachePeriodInMinutes', ], 'members' => [ 'Address' => [ 'shape' => 'String', ], 'CachePeriodInMinutes' => [ 'shape' => 'Long', ], ], ], 'Endpoints' => [ 'type' => 'list', 'member' => [ 'shape' => 'Endpoint', ], ], 'ErrorMessage' => [ 'type' => 'string', ], 'ErrorReportConfiguration' => [ 'type' => 'structure', 'required' => [ 'S3Configuration', ], 'members' => [ 'S3Configuration' => [ 'shape' => 'S3Configuration', ], ], ], 'ErrorReportLocation' => [ 'type' => 'structure', 'members' => [ 'S3ReportLocation' => [ 'shape' => 'S3ReportLocation', ], ], ], 'ExecuteScheduledQueryRequest' => [ 'type' => 'structure', 'required' => [ 'ScheduledQueryArn', 'InvocationTime', ], 'members' => [ 'ScheduledQueryArn' => [ 'shape' => 'AmazonResourceName', ], 'InvocationTime' => [ 'shape' => 'Time', ], 'ClientToken' => [ 'shape' => 'ClientToken', 'idempotencyToken' => true, ], ], ], 'ExecutionStats' => [ 'type' => 'structure', 'members' => [ 'ExecutionTimeInMillis' => [ 'shape' => 'Long', ], 'DataWrites' => [ 'shape' => 'Long', ], 'BytesMetered' => [ 'shape' => 'Long', ], 'RecordsIngested' => [ 'shape' => 'Long', ], 'QueryResultRows' => [ 'shape' => 'Long', ], ], ], 'InternalServerException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], 'InvalidEndpointException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], 'ListScheduledQueriesRequest' => [ 'type' => 'structure', 'members' => [ 'MaxResults' => [ 'shape' => 'MaxScheduledQueriesResults', ], 'NextToken' => [ 'shape' => 'NextScheduledQueriesResultsToken', ], ], ], 'ListScheduledQueriesResponse' => [ 'type' => 'structure', 'required' => [ 'ScheduledQueries', ], 'members' => [ 'ScheduledQueries' => [ 'shape' => 'ScheduledQueryList', ], 'NextToken' => [ 'shape' => 'NextScheduledQueriesResultsToken', ], ], ], 'ListTagsForResourceRequest' => [ 'type' => 'structure', 'required' => [ 'ResourceARN', ], 'members' => [ 'ResourceARN' => [ 'shape' => 'AmazonResourceName', ], 'MaxResults' => [ 'shape' => 'MaxTagsForResourceResult', ], 'NextToken' => [ 'shape' => 'NextTagsForResourceResultsToken', ], ], ], 'ListTagsForResourceResponse' => [ 'type' => 'structure', 'required' => [ 'Tags', ], 'members' => [ 'Tags' => [ 'shape' => 'TagList', ], 'NextToken' => [ 'shape' => 'NextTagsForResourceResultsToken', ], ], ], 'Long' => [ 'type' => 'long', ], 'MaxQueryResults' => [ 'type' => 'integer', 'box' => true, 'max' => 1000, 'min' => 1, ], 'MaxScheduledQueriesResults' => [ 'type' => 'integer', 'box' => true, 'max' => 1000, 'min' => 1, ], 'MaxTagsForResourceResult' => [ 'type' => 'integer', 'box' => true, 'max' => 200, 'min' => 1, ], 'MeasureValueType' => [ 'type' => 'string', 'enum' => [ 'BIGINT', 'BOOLEAN', 'DOUBLE', 'VARCHAR', 'MULTI', ], ], 'MixedMeasureMapping' => [ 'type' => 'structure', 'required' => [ 'MeasureValueType', ], 'members' => [ 'MeasureName' => [ 'shape' => 'SchemaName', ], 'SourceColumn' => [ 'shape' => 'SchemaName', ], 'TargetMeasureName' => [ 'shape' => 'SchemaName', ], 'MeasureValueType' => [ 'shape' => 'MeasureValueType', ], 'MultiMeasureAttributeMappings' => [ 'shape' => 'MultiMeasureAttributeMappingList', ], ], ], 'MixedMeasureMappingList' => [ 'type' => 'list', 'member' => [ 'shape' => 'MixedMeasureMapping', ], 'min' => 1, ], 'MultiMeasureAttributeMapping' => [ 'type' => 'structure', 'required' => [ 'SourceColumn', 'MeasureValueType', ], 'members' => [ 'SourceColumn' => [ 'shape' => 'SchemaName', ], 'TargetMultiMeasureAttributeName' => [ 'shape' => 'SchemaName', ], 'MeasureValueType' => [ 'shape' => 'ScalarMeasureValueType', ], ], ], 'MultiMeasureAttributeMappingList' => [ 'type' => 'list', 'member' => [ 'shape' => 'MultiMeasureAttributeMapping', ], 'min' => 1, ], 'MultiMeasureMappings' => [ 'type' => 'structure', 'required' => [ 'MultiMeasureAttributeMappings', ], 'members' => [ 'TargetMultiMeasureName' => [ 'shape' => 'SchemaName', ], 'MultiMeasureAttributeMappings' => [ 'shape' => 'MultiMeasureAttributeMappingList', ], ], ], 'NextScheduledQueriesResultsToken' => [ 'type' => 'string', ], 'NextTagsForResourceResultsToken' => [ 'type' => 'string', ], 'NotificationConfiguration' => [ 'type' => 'structure', 'required' => [ 'SnsConfiguration', ], 'members' => [ 'SnsConfiguration' => [ 'shape' => 'SnsConfiguration', ], ], ], 'NullableBoolean' => [ 'type' => 'boolean', 'box' => true, ], 'PaginationToken' => [ 'type' => 'string', 'max' => 2048, 'min' => 1, ], 'ParameterMapping' => [ 'type' => 'structure', 'required' => [ 'Name', 'Type', ], 'members' => [ 'Name' => [ 'shape' => 'String', ], 'Type' => [ 'shape' => 'Type', ], ], ], 'ParameterMappingList' => [ 'type' => 'list', 'member' => [ 'shape' => 'ParameterMapping', ], ], 'PrepareQueryRequest' => [ 'type' => 'structure', 'required' => [ 'QueryString', ], 'members' => [ 'QueryString' => [ 'shape' => 'QueryString', ], 'ValidateOnly' => [ 'shape' => 'NullableBoolean', ], ], ], 'PrepareQueryResponse' => [ 'type' => 'structure', 'required' => [ 'QueryString', 'Columns', 'Parameters', ], 'members' => [ 'QueryString' => [ 'shape' => 'QueryString', ], 'Columns' => [ 'shape' => 'SelectColumnList', ], 'Parameters' => [ 'shape' => 'ParameterMappingList', ], ], ], 'QueryExecutionException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], 'QueryId' => [ 'type' => 'string', 'max' => 64, 'min' => 1, 'pattern' => '[a-zA-Z0-9]+', ], 'QueryRequest' => [ 'type' => 'structure', 'required' => [ 'QueryString', ], 'members' => [ 'QueryString' => [ 'shape' => 'QueryString', ], 'ClientToken' => [ 'shape' => 'ClientRequestToken', 'idempotencyToken' => true, ], 'NextToken' => [ 'shape' => 'PaginationToken', ], 'MaxRows' => [ 'shape' => 'MaxQueryResults', ], ], ], 'QueryResponse' => [ 'type' => 'structure', 'required' => [ 'QueryId', 'Rows', 'ColumnInfo', ], 'members' => [ 'QueryId' => [ 'shape' => 'QueryId', ], 'NextToken' => [ 'shape' => 'PaginationToken', ], 'Rows' => [ 'shape' => 'RowList', ], 'ColumnInfo' => [ 'shape' => 'ColumnInfoList', ], 'QueryStatus' => [ 'shape' => 'QueryStatus', ], ], ], 'QueryStatus' => [ 'type' => 'structure', 'members' => [ 'ProgressPercentage' => [ 'shape' => 'Double', ], 'CumulativeBytesScanned' => [ 'shape' => 'Long', ], 'CumulativeBytesMetered' => [ 'shape' => 'Long', ], ], ], 'QueryString' => [ 'type' => 'string', 'max' => 262144, 'min' => 1, 'sensitive' => true, ], 'ResourceName' => [ 'type' => 'string', ], 'ResourceNotFoundException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], 'ScheduledQueryArn' => [ 'shape' => 'AmazonResourceName', ], ], 'exception' => true, ], 'Row' => [ 'type' => 'structure', 'required' => [ 'Data', ], 'members' => [ 'Data' => [ 'shape' => 'DatumList', ], ], ], 'RowList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Row', ], ], 'S3BucketName' => [ 'type' => 'string', 'max' => 63, 'min' => 3, 'pattern' => '[a-z0-9][\\.\\-a-z0-9]{1,61}[a-z0-9]', ], 'S3Configuration' => [ 'type' => 'structure', 'required' => [ 'BucketName', ], 'members' => [ 'BucketName' => [ 'shape' => 'S3BucketName', ], 'ObjectKeyPrefix' => [ 'shape' => 'S3ObjectKeyPrefix', ], 'EncryptionOption' => [ 'shape' => 'S3EncryptionOption', ], ], ], 'S3EncryptionOption' => [ 'type' => 'string', 'enum' => [ 'SSE_S3', 'SSE_KMS', ], ], 'S3ObjectKey' => [ 'type' => 'string', ], 'S3ObjectKeyPrefix' => [ 'type' => 'string', 'max' => 896, 'min' => 1, 'pattern' => '[a-zA-Z0-9|!\\-_*\'\\(\\)]([a-zA-Z0-9]|[!\\-_*\'\\(\\)\\/.])+', ], 'S3ReportLocation' => [ 'type' => 'structure', 'members' => [ 'BucketName' => [ 'shape' => 'S3BucketName', ], 'ObjectKey' => [ 'shape' => 'S3ObjectKey', ], ], ], 'ScalarMeasureValueType' => [ 'type' => 'string', 'enum' => [ 'BIGINT', 'BOOLEAN', 'DOUBLE', 'VARCHAR', ], ], 'ScalarType' => [ 'type' => 'string', 'enum' => [ 'VARCHAR', 'BOOLEAN', 'BIGINT', 'DOUBLE', 'TIMESTAMP', 'DATE', 'TIME', 'INTERVAL_DAY_TO_SECOND', 'INTERVAL_YEAR_TO_MONTH', 'UNKNOWN', 'INTEGER', ], ], 'ScalarValue' => [ 'type' => 'string', ], 'ScheduleConfiguration' => [ 'type' => 'structure', 'required' => [ 'ScheduleExpression', ], 'members' => [ 'ScheduleExpression' => [ 'shape' => 'ScheduleExpression', ], ], ], 'ScheduleExpression' => [ 'type' => 'string', 'max' => 256, 'min' => 1, ], 'ScheduledQuery' => [ 'type' => 'structure', 'required' => [ 'Arn', 'Name', 'State', ], 'members' => [ 'Arn' => [ 'shape' => 'AmazonResourceName', ], 'Name' => [ 'shape' => 'ScheduledQueryName', ], 'CreationTime' => [ 'shape' => 'Time', ], 'State' => [ 'shape' => 'ScheduledQueryState', ], 'PreviousInvocationTime' => [ 'shape' => 'Time', ], 'NextInvocationTime' => [ 'shape' => 'Time', ], 'ErrorReportConfiguration' => [ 'shape' => 'ErrorReportConfiguration', ], 'TargetDestination' => [ 'shape' => 'TargetDestination', ], 'LastRunStatus' => [ 'shape' => 'ScheduledQueryRunStatus', ], ], ], 'ScheduledQueryDescription' => [ 'type' => 'structure', 'required' => [ 'Arn', 'Name', 'QueryString', 'State', 'ScheduleConfiguration', 'NotificationConfiguration', ], 'members' => [ 'Arn' => [ 'shape' => 'AmazonResourceName', ], 'Name' => [ 'shape' => 'ScheduledQueryName', ], 'QueryString' => [ 'shape' => 'QueryString', ], 'CreationTime' => [ 'shape' => 'Time', ], 'State' => [ 'shape' => 'ScheduledQueryState', ], 'PreviousInvocationTime' => [ 'shape' => 'Time', ], 'NextInvocationTime' => [ 'shape' => 'Time', ], 'ScheduleConfiguration' => [ 'shape' => 'ScheduleConfiguration', ], 'NotificationConfiguration' => [ 'shape' => 'NotificationConfiguration', ], 'TargetConfiguration' => [ 'shape' => 'TargetConfiguration', ], 'ScheduledQueryExecutionRoleArn' => [ 'shape' => 'AmazonResourceName', ], 'KmsKeyId' => [ 'shape' => 'StringValue2048', ], 'ErrorReportConfiguration' => [ 'shape' => 'ErrorReportConfiguration', ], 'LastRunSummary' => [ 'shape' => 'ScheduledQueryRunSummary', ], 'RecentlyFailedRuns' => [ 'shape' => 'ScheduledQueryRunSummaryList', ], ], ], 'ScheduledQueryList' => [ 'type' => 'list', 'member' => [ 'shape' => 'ScheduledQuery', ], ], 'ScheduledQueryName' => [ 'type' => 'string', 'max' => 64, 'min' => 1, 'pattern' => '[a-zA-Z0-9_.-]+', ], 'ScheduledQueryRunStatus' => [ 'type' => 'string', 'enum' => [ 'AUTO_TRIGGER_SUCCESS', 'AUTO_TRIGGER_FAILURE', 'MANUAL_TRIGGER_SUCCESS', 'MANUAL_TRIGGER_FAILURE', ], ], 'ScheduledQueryRunSummary' => [ 'type' => 'structure', 'members' => [ 'InvocationTime' => [ 'shape' => 'Time', ], 'TriggerTime' => [ 'shape' => 'Time', ], 'RunStatus' => [ 'shape' => 'ScheduledQueryRunStatus', ], 'ExecutionStats' => [ 'shape' => 'ExecutionStats', ], 'ErrorReportLocation' => [ 'shape' => 'ErrorReportLocation', ], 'FailureReason' => [ 'shape' => 'ErrorMessage', ], ], ], 'ScheduledQueryRunSummaryList' => [ 'type' => 'list', 'member' => [ 'shape' => 'ScheduledQueryRunSummary', ], ], 'ScheduledQueryState' => [ 'type' => 'string', 'enum' => [ 'ENABLED', 'DISABLED', ], ], 'SchemaName' => [ 'type' => 'string', ], 'SelectColumn' => [ 'type' => 'structure', 'members' => [ 'Name' => [ 'shape' => 'String', ], 'Type' => [ 'shape' => 'Type', ], 'DatabaseName' => [ 'shape' => 'ResourceName', ], 'TableName' => [ 'shape' => 'ResourceName', ], 'Aliased' => [ 'shape' => 'NullableBoolean', ], ], ], 'SelectColumnList' => [ 'type' => 'list', 'member' => [ 'shape' => 'SelectColumn', ], ], 'ServiceErrorMessage' => [ 'type' => 'string', ], 'ServiceQuotaExceededException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], 'SnsConfiguration' => [ 'type' => 'structure', 'required' => [ 'TopicArn', ], 'members' => [ 'TopicArn' => [ 'shape' => 'AmazonResourceName', ], ], ], 'String' => [ 'type' => 'string', ], 'StringValue2048' => [ 'type' => 'string', 'max' => 2048, 'min' => 1, ], 'Tag' => [ 'type' => 'structure', 'required' => [ 'Key', 'Value', ], 'members' => [ 'Key' => [ 'shape' => 'TagKey', ], 'Value' => [ 'shape' => 'TagValue', ], ], ], 'TagKey' => [ 'type' => 'string', 'max' => 128, 'min' => 1, ], 'TagKeyList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TagKey', ], 'max' => 200, 'min' => 0, ], 'TagList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Tag', ], 'max' => 200, 'min' => 0, ], 'TagResourceRequest' => [ 'type' => 'structure', 'required' => [ 'ResourceARN', 'Tags', ], 'members' => [ 'ResourceARN' => [ 'shape' => 'AmazonResourceName', ], 'Tags' => [ 'shape' => 'TagList', ], ], ], 'TagResourceResponse' => [ 'type' => 'structure', 'members' => [], ], 'TagValue' => [ 'type' => 'string', 'max' => 256, 'min' => 0, ], 'TargetConfiguration' => [ 'type' => 'structure', 'required' => [ 'TimestreamConfiguration', ], 'members' => [ 'TimestreamConfiguration' => [ 'shape' => 'TimestreamConfiguration', ], ], ], 'TargetDestination' => [ 'type' => 'structure', 'members' => [ 'TimestreamDestination' => [ 'shape' => 'TimestreamDestination', ], ], ], 'ThrottlingException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], 'Time' => [ 'type' => 'timestamp', ], 'TimeSeriesDataPoint' => [ 'type' => 'structure', 'required' => [ 'Time', 'Value', ], 'members' => [ 'Time' => [ 'shape' => 'Timestamp', ], 'Value' => [ 'shape' => 'Datum', ], ], ], 'TimeSeriesDataPointList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TimeSeriesDataPoint', ], ], 'Timestamp' => [ 'type' => 'string', ], 'TimestreamConfiguration' => [ 'type' => 'structure', 'required' => [ 'DatabaseName', 'TableName', 'TimeColumn', 'DimensionMappings', ], 'members' => [ 'DatabaseName' => [ 'shape' => 'ResourceName', ], 'TableName' => [ 'shape' => 'ResourceName', ], 'TimeColumn' => [ 'shape' => 'SchemaName', ], 'DimensionMappings' => [ 'shape' => 'DimensionMappingList', ], 'MultiMeasureMappings' => [ 'shape' => 'MultiMeasureMappings', ], 'MixedMeasureMappings' => [ 'shape' => 'MixedMeasureMappingList', ], 'MeasureNameColumn' => [ 'shape' => 'SchemaName', ], ], ], 'TimestreamDestination' => [ 'type' => 'structure', 'members' => [ 'DatabaseName' => [ 'shape' => 'ResourceName', ], 'TableName' => [ 'shape' => 'ResourceName', ], ], ], 'Type' => [ 'type' => 'structure', 'members' => [ 'ScalarType' => [ 'shape' => 'ScalarType', ], 'ArrayColumnInfo' => [ 'shape' => 'ColumnInfo', ], 'TimeSeriesMeasureValueColumnInfo' => [ 'shape' => 'ColumnInfo', ], 'RowColumnInfo' => [ 'shape' => 'ColumnInfoList', ], ], ], 'UntagResourceRequest' => [ 'type' => 'structure', 'required' => [ 'ResourceARN', 'TagKeys', ], 'members' => [ 'ResourceARN' => [ 'shape' => 'AmazonResourceName', ], 'TagKeys' => [ 'shape' => 'TagKeyList', ], ], ], 'UntagResourceResponse' => [ 'type' => 'structure', 'members' => [], ], 'UpdateScheduledQueryRequest' => [ 'type' => 'structure', 'required' => [ 'ScheduledQueryArn', 'State', ], 'members' => [ 'ScheduledQueryArn' => [ 'shape' => 'AmazonResourceName', ], 'State' => [ 'shape' => 'ScheduledQueryState', ], ], ], 'ValidationException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ErrorMessage', ], ], 'exception' => true, ], ],];
